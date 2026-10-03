<?php
// Live SEO checks for /seo-health/: fetch the sitemap, then request the listed pages over HTTP
// the way a crawler would and report what a crawler would see. Only the site's own URLs are fetched.

/** Fetch many URLs in parallel (no redirect following). Returns url => [status, ttfb, body, headers, error]. */
function scan_fetch(array $urls, int $parallel = 8): array
{
    $out = [];
    $mh = curl_multi_init();
    $queue = array_values($urls);
    $active = [];
    $start = function () use (&$queue, &$active, $mh) {
        $u = array_shift($queue);
        $ch = curl_init($u);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false, CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 25, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_ENCODING => '',
            CURLOPT_USERAGENT => 'IPAGameStore-SEOHealth/1.0',
        ]);
        curl_multi_add_handle($mh, $ch);
        $active[(int) $ch] = [$ch, $u];
    };
    while ($queue && count($active) < $parallel) $start();
    while ($active) {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh, 1.0);
        while ($done = curl_multi_info_read($mh)) {
            [$ch, $u] = $active[(int) $done['handle']];
            $raw  = (string) curl_multi_getcontent($ch);
            $hs   = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $out[$u] = [
                'status'  => (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE),
                'ttfb'    => (float) curl_getinfo($ch, CURLINFO_STARTTRANSFER_TIME),
                'headers' => substr($raw, 0, $hs),
                'body'    => substr($raw, $hs),
                'error'   => $done['result'] !== CURLE_OK ? curl_error($ch) : '',
            ];
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
            unset($active[(int) $done['handle']]);
            if ($queue) $start();
        }
    }
    curl_multi_close($mh);
    return $out;
}

/** Normalise a URL for comparison (decode %20, drop trailing-slash difference on the root). */
function scan_norm(string $u): string { return rtrim(rawurldecode(trim($u)), '/'); }

/** Inspect one fetched page. Returns a list of [label, severity, hint, note]. */
function scan_page(string $url, array $r): array
{
    $f = [];
    $st = $r['status'];
    if ($r['error'] !== '' || $st === 0) return [['Page could not be fetched', 'high', 'The request failed or timed out.', $r['error'] ?: 'no response']];
    if ($st >= 300 && $st < 400) { preg_match('/^location:\s*(\S+)/im', $r['headers'], $m); return [['Sitemap URL redirects', 'high', 'Sitemap URLs must return 200. Put the final URL in the sitemap.', "HTTP $st → " . ($m[1] ?? '?')]]; }
    if ($st !== 200) return [['Sitemap URL is not 200', 'high', 'Broken or error pages must not be in the sitemap.', "HTTP $st"]];

    if ($r['ttfb'] > 1.5) $f[] = ['Slow server response', 'warn', 'Over 1.5 s until the first byte. Slow pages get crawled less.', round($r['ttfb'], 1) . ' s'];
    if (preg_match('/^x-robots-tag:.*noindex/im', $r['headers'])) $f[] = ['Noindex in sitemap page', 'high', 'X-Robots-Tag noindex on a page listed in the sitemap.', 'header'];

    $prev = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8"?>' . $r['body']);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    $xp = new DOMXPath($dom);

    foreach ($xp->query('//meta[@name="robots"]/@content') as $a)
        if (stripos($a->value, 'noindex') !== false) $f[] = ['Noindex in sitemap page', 'high', 'A page listed in the sitemap tells Google not to index it.', $a->value];

    $canon = $xp->query('//link[@rel="canonical"]/@href');
    if (!$canon->length) $f[] = ['Missing canonical', 'high', 'Every indexable page needs one canonical URL.', ''];
    elseif ($canon->length > 1) $f[] = ['More than one canonical', 'high', 'Only one canonical tag is allowed.', $canon->length . ' tags'];
    elseif (scan_norm($canon->item(0)->nodeValue) !== scan_norm($url)) $f[] = ['Canonical differs from sitemap URL', 'high', 'The page points Google at another URL.', $canon->item(0)->nodeValue];

    $title = trim((string) $xp->evaluate('string(//title)'));
    if ($title === '') $f[] = ['Missing title', 'high', 'No <title> tag.', ''];
    elseif (mb_strlen($title) > 65) $f[] = ['Title too long (live)', 'warn', 'Google cuts titles around 60 characters.', mb_strlen($title) . ' chars'];
    $desc = trim((string) $xp->evaluate('string(//meta[@name="description"]/@content)'));
    if ($desc === '') $f[] = ['Missing meta description', 'warn', 'No meta description.', ''];

    $h1 = (int) $xp->evaluate('count(//h1)');
    if ($h1 !== 1) $f[] = ['H1 count is not 1', 'warn', 'Each page should have exactly one H1.', "$h1 found"];

    foreach ($xp->query('//script[@type="application/ld+json"]') as $s)
        if (json_decode($s->textContent) === null) { $f[] = ['Invalid JSON-LD', 'high', 'A structured-data block is not valid JSON.', json_last_error_msg()]; break; }

    $noAlt = (int) $xp->evaluate('count(//img[not(@alt)])');
    if ($noAlt) $f[] = ['Images without alt attribute', 'warn', 'Add alt="" for decorative images and a description for content images.', "$noAlt images"];
    return $f;
}

/**
 * Full scan. $sample = how many game pages to check (all other sitemap URLs are always checked).
 * Returns ['issues' => label => [sev, hint, rows], 'summary' => [...]].
 */
function seo_scan(int $sample): array
{
    @set_time_limit(280);
    $issues = [];
    $push = function (string $label, string $sev, string $hint, string $name, string $url, string $note = '') use (&$issues) {
        $issues[$label] ??= [$sev, $hint, []];
        $issues[$label][2][] = [$name, $url, $note];
    };

    $smUrl = abs_url('sitemap.xml');
    $sm = scan_fetch([$smUrl], 1)[$smUrl];
    if ($sm['status'] !== 200) {
        $push('Sitemap not reachable', 'high', 'sitemap.xml must return 200.', 'sitemap.xml', $smUrl, 'HTTP ' . $sm['status'] . ' ' . $sm['error']);
        return ['issues' => $issues, 'summary' => ['Sitemap URLs' => 0, 'Pages checked' => 0]];
    }
    $prev = libxml_use_internal_errors(true);
    $xml = simplexml_load_string($sm['body']);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    if (!$xml) {
        $push('Sitemap is not valid XML', 'high', 'Google cannot read it.', 'sitemap.xml', $smUrl);
        return ['issues' => $issues, 'summary' => ['Sitemap URLs' => 0, 'Pages checked' => 0]];
    }

    $locs = [];
    foreach ($xml->url as $u) $locs[] = trim((string) $u->loc);
    $host = parse_url(SITE_URL, PHP_URL_HOST);
    $seen = [];
    foreach ($locs as $l) {
        if (isset($seen[$l])) $push('Duplicate URL in sitemap', 'warn', 'The same URL is listed twice.', $l, $l);
        $seen[$l] = true;
        if (parse_url($l, PHP_URL_HOST) !== $host) $push('Sitemap URL on another host', 'high', 'Sitemap URLs must be on the site\'s own host.', $l, $l);
        if (strlen($l) > 2048) $push('Sitemap URL too long', 'high', 'Over 2048 characters.', $l, $l);
    }
    $locs = array_values(array_filter(array_unique($locs), fn($l) => parse_url($l, PHP_URL_HOST) === $host));

    $games = array_values(array_filter($locs, fn($l) => (bool) preg_match('~/ipa-games/[^/]+/[^/]+-ipa/$~', $l)));
    $other = array_values(array_diff($locs, $games));
    shuffle($games);
    $targets = array_merge($other, array_slice($games, 0, $sample));

    $results = scan_fetch($targets);
    foreach ($targets as $u) {
        $name = rawurldecode(substr($u, strlen(SITE_URL))) ?: '/';
        foreach (scan_page($u, $results[$u]) as [$label, $sev, $hint, $note]) $push($label, $sev, $hint, $name, $u, $note);
    }

    $order = ['high' => 0, 'warn' => 1, 'info' => 2];
    uasort($issues, fn($a, $b) => $order[$a[0]] <=> $order[$b[0]] ?: count($b[2]) <=> count($a[2]));
    return ['issues' => $issues, 'summary' => [
        'Sitemap URLs' => count($locs), 'Game pages in sitemap' => count($games),
        'Pages checked' => count($targets), 'Pages with problems' => count(array_unique(array_merge([], ...array_map(fn($i) => array_column($i[2], 1), array_values($issues))))),
    ]];
}
