<?php
header('Content-Type: application/xml; charset=utf-8');
$rel = fn($u) => SITE_URL . substr($u, strlen(str_replace(' ', '%20', BASE_PATH)));
$row = fn($loc, $mod = null) => "  <url><loc>" . e($loc) . "</loc>" . ($mod ? "<lastmod>" . date('Y-m-d', strtotime($mod)) . "</lastmod>" : '') . "</url>\n";
$fileDate = fn($f) => is_file($f) ? date('Y-m-d', filemtime($f)) : null;

// lastmod = when the listed games last changed, so Google can trust it.
$pub = "status='published'";            // games and apps
$pubApps = "status='published' AND type='app'";
$newest = db()->query("SELECT MAX(updated_at) FROM games WHERE $pub")->fetchColumn();
$byCat  = db()->query("SELECT category, MAX(updated_at) FROM games WHERE $pub AND type='game' GROUP BY category")->fetchAll(PDO::FETCH_KEY_PAIR);
$byCatApp = db()->query("SELECT category, MAX(updated_at) FROM games WHERE $pubApps GROUP BY category")->fetchAll(PDO::FETCH_KEY_PAIR);
$counts = category_counts();
$appCounts = app_category_counts();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
echo $row(abs_url(), $newest);
echo $row(abs_url('ipa-games/'), $newest);
foreach (categories() as $s => $c)
    // Thin categories are noindex (routes/category.php), so they stay out of the sitemap too.
    if (($counts[$s] ?? 0) >= MIN_INDEXABLE_GAMES) echo $row(abs_url("ipa-games/$s/"), empty($c['special']) ? ($byCat[$s] ?? $newest) : $newest);
if (array_sum($appCounts) > 0) {
    echo $row(abs_url('ipa-apps/'), $byCatApp ? max($byCatApp) : $newest);
    foreach (app_categories() as $s => $c)
        if (($appCounts[$s] ?? 0) >= MIN_INDEXABLE_GAMES) echo $row(abs_url("ipa-apps/$s/"), empty($c['special']) ? ($byCatApp[$s] ?? $newest) : ($byCatApp ? max($byCatApp) : $newest));
}
echo $row(abs_url('download-ipastore/'), $fileDate(__DIR__ . '/../views/ipastore.php'));
echo $row(abs_url('guides/'), max(array_map(fn($s) => $fileDate(__DIR__ . "/../content/guides/$s.php"), array_keys(guides()))));
foreach (guides() as $s => $g) echo $row(abs_url("guides/$s/"), $fileDate(__DIR__ . "/../content/guides/$s.php"));
// Games switched to noindex in the admin stay out of the sitemap (column exists after migration 004).
$noindex = db()->query("SHOW COLUMNS FROM games LIKE 'seo_noindex'")->fetch() ? ' AND seo_noindex=0' : '';
foreach (db()->query("SELECT slug, type, category, updated_at FROM games WHERE $pub$noindex ORDER BY id") as $g)
    echo $row($rel(game_url($g)), $g['updated_at']);
foreach (['about', 'how-we-verify', 'terms', 'dmca', 'disclaimer', 'privacy', 'contact'] as $p) echo $row(abs_url("$p/"));
echo "</urlset>\n";
