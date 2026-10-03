<?php
// Private SEO health report. Locked by config 'seo_health_key' (empty = page does not exist),
// never linked from the site, noindex. Reads the database only; it crawls nothing.
$key = (string) cfg('seo_health_key');
if ($key === '') not_found();

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-LiteSpeed-Cache-Control: no-cache');
session_name('seohealth');
session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Strict', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
session_start();

$self = url('seo-health/');
if (isset($_GET['logout'])) { $_SESSION = []; session_destroy(); redirect($self, 302); }

$loginError = false;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !isset($_POST['scan'])) {
    if (hash_equals($key, (string) ($_POST['key'] ?? ''))) { session_regenerate_id(true); $_SESSION['ok'] = true; redirect($self, 302); }
    sleep(1);   // slows down guessing
    $loginError = true;
}
$authed = !empty($_SESSION['ok']);
$_SESSION['csrf'] ??= bin2hex(random_bytes(16));

// Live scan (button on the page). Post → run → redirect, so a refresh never re-runs it.
if ($authed && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['scan'])) {
    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Bad request'); }
    require __DIR__ . '/../app/seo-scan.php';
    $res = seo_scan(in_array((int) $_POST['sample'], [50, 200, 500], true) ? (int) $_POST['sample'] : 50);
    foreach ($res['issues'] as &$i) $i[2] = array_slice($i[2], 0, 200);   // keep the session small
    unset($i);
    $_SESSION['scan'] = $res + ['at' => date('M j, Y H:i') . ' UTC'];
    redirect($self . '#live', 302);
}
// Search Console data (button on the page). Same post → run → redirect pattern.
if ($authed && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['gsc'])) {
    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Bad request'); }
    require __DIR__ . '/../app/gsc.php';
    try {
        $days = in_array((int) $_POST['days'], [7, 28, 90], true) ? (int) $_POST['days'] : 28;
        $r = gsc_report($days);
        $r['pages'] = array_slice($r['pages'], 0, 300);
        $_SESSION['gsc'] = $r + ['at' => date('M j, Y H:i') . ' UTC'];
    } catch (Throwable $ex) {
        $_SESSION['gsc'] = ['error' => $ex->getMessage(), 'at' => date('M j, Y H:i') . ' UTC'];
    }
    redirect($self . '#gsc', 302);
}
if ($authed) require_once __DIR__ . '/../app/gsc.php';
$gscReady = $authed && gsc_configured();
$gsc = $authed ? ($_SESSION['gsc'] ?? null) : null;
$scan = $authed ? ($_SESSION['scan'] ?? null) : null;

$issues = [];      // label => [severity, hint, rows[ [name, url, note] ]]
$stats  = [];
if ($authed) {
    $rows = db()->query("SELECT g.*,
            (SELECT COUNT(*) FROM game_screenshots s WHERE s.game_id=g.id) AS shot_count,
            EXISTS(SELECT 1 FROM game_versions v WHERE v.game_id=g.id AND v.download_url<>'' AND v.download_url NOT LIKE '%apps.apple.com%') AS is_ipa
        FROM games g WHERE g.status='published' AND g.type='game' ORDER BY g.name")->fetchAll();

    $add = function (string $label, string $sev, string $hint, array $g, string $note = '') use (&$issues) {
        $issues[$label] ??= [$sev, $hint, []];
        $issues[$label][2][] = [game_name($g), game_url($g), $note];
    };

    $titles = $descs = [];
    $indexable = 0;
    foreach ($rows as $g) {
        [$title, $desc] = game_seo($g, (bool) $g['is_ipa']);
        $full = str_contains($title, SITE_NAME) || mb_strlen("$title | " . SITE_NAME) > 60 ? $title : "$title | " . SITE_NAME;
        $titles[mb_strtolower($full)][] = $g;
        $descs[mb_strtolower($desc)][]  = $g;

        if (!empty($g['seo_noindex'])) $add('Set to noindex', 'info', 'These pages are hidden from Google and the sitemap on purpose.', $g);
        else $indexable++;
        if (mb_strlen($full) > 65) $add('Title too long', 'warn', 'Google cuts titles around 60 characters. Shorten the name.', $g, mb_strlen($full) . ' chars: ' . $full);
        if (mb_strlen($desc) < 70) $add('Meta description too short', 'warn', 'Aim for 100–158 characters.', $g, mb_strlen($desc) . ' chars');

        if ($g['shot_count'] == 0) $add('No screenshots', 'high', 'Game pages without screenshots look thin and lose image traffic.', $g);
        if (trim((string) $g['description']) === '') $add('No description', 'high', 'The About section is empty, so the page has little unique text.', $g);
        elseif (mb_strlen(strip_tags($g['description'])) < 200) $add('Very short description', 'warn', 'Under 200 characters of unique text.', $g, mb_strlen(strip_tags($g['description'])) . ' chars');
        if (trim((string) $g['short_description']) === '') $add('No short description', 'warn', 'Used on cards and search results.', $g);
        if (trim((string) $g['icon']) === '') $add('No icon', 'high', 'Used as the cover and social-share image.', $g);
        if (trim((string) $g['latest_version']) === '') $add('No version', 'warn', 'Version shows in titles and schema.', $g);
        if ((float) $g['latest_size_mb'] <= 0) $add('No file size', 'warn', 'Shown on the page and in schema.', $g);
        if (trim((string) $g['min_ios']) === '') $add('No minimum iOS', 'warn', 'The FAQ and compatibility text fall back to generic wording.', $g);
        if (trim((string) $g['developer']) === '') $add('No developer', 'warn', 'Developer is shown on the page and in schema.', $g);
        if (empty($g['latest_release_date'])) $add('No release date', 'warn', 'Needed for the Latest list and sitemap freshness.', $g);
    }
    foreach ([['Duplicate title', $titles], ['Duplicate meta description', $descs]] as [$label, $map])
        foreach ($map as $text => $list) if (count($list) > 1)
            foreach ($list as $g) $add($label, 'high', 'Two or more pages share the same text.', $g, $text);

    // Categories: thin ones are noindex and left out of the sitemap.
    $counts = category_counts();
    $thin = [];
    foreach (categories() as $s => $c) if (($counts[$s] ?? 0) < MIN_INDEXABLE_GAMES) $thin[] = [$c['name'], category_url($s), (int) ($counts[$s] ?? 0) . ' games'];
    if ($thin) $issues['Thin categories (noindex)'] = ['info', 'Fewer than ' . MIN_INDEXABLE_GAMES . ' games, so they are noindex and not in the sitemap.', $thin];

    // Guides
    $gt = $gd = [];
    foreach (guides() as $s => $gu) {
        $row = [$gu['short'], guide_url($s)];
        if (mb_strlen($gu['title']) > 65) {
            $issues['Guide title too long'] ??= ['warn', 'Over 65 characters.', []];
            $issues['Guide title too long'][2][] = [...$row, mb_strlen($gu['title']) . ' chars'];
        }
        if (mb_strlen($gu['desc']) < 70 || mb_strlen($gu['desc']) > 165) {
            $issues['Guide description length'] ??= ['warn', 'Aim for 70–160 characters.', []];
            $issues['Guide description length'][2][] = [...$row, mb_strlen($gu['desc']) . ' chars'];
        }
        $gt[$gu['title']][] = $row;
        $gd[$gu['desc']][] = $row;
    }
    foreach ([['Duplicate guide title', $gt], ['Duplicate guide description', $gd]] as [$label, $map])
        foreach ($map as $list) if (count($list) > 1) foreach ($list as $r) {
            $issues[$label] ??= ['high', 'Shared by more than one guide.', []];
            $issues[$label][2][] = $r;
        }

    $stats = [
        'Published games' => count($rows),
        'Indexable games (in sitemap)' => $indexable,
        'Indexable categories' => count(array_filter(categories(), fn($c, $s) => ($counts[$s] ?? 0) >= MIN_INDEXABLE_GAMES, ARRAY_FILTER_USE_BOTH)),
        'Guides' => count(guides()),
        'Games with a screenshot' => count(array_filter($rows, fn($g) => $g['shot_count'] > 0)),
    ];
    $order = ['high' => 0, 'warn' => 1, 'info' => 2];
    uasort($issues, fn($a, $b) => $order[$a[0]] <=> $order[$b[0]] ?: count($b[2]) <=> count($a[2]));
}

require __DIR__ . '/../views/seo-health.php';
