<?php
header('Content-Type: application/xml; charset=utf-8');
$rel = fn($u) => SITE_URL . substr($u, strlen(str_replace(' ', '%20', BASE_PATH)));
$row = fn($loc, $mod = null, $pri = '0.5') => "  <url><loc>" . e($loc) . "</loc>" . ($mod ? "<lastmod>" . date('Y-m-d', strtotime($mod)) . "</lastmod>" : '') . "<priority>$pri</priority></url>\n";

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
echo $row(abs_url(), date('Y-m-d'), '1.0');
echo $row(abs_url('ipa-games/'), date('Y-m-d'), '0.9');
foreach (array_keys(categories()) as $s)
    if (games_count(category($s)['where']) > 0) echo $row(abs_url("ipa-games/$s/"), date('Y-m-d'), '0.8');
echo $row(abs_url('download-ipastore/'), null, '0.9');
echo $row(abs_url('guides/'), null, '0.8');
foreach (guides() as $s => $g) echo $row(abs_url("guides/$s/"), null, '0.8');
foreach (db()->query("SELECT slug, category, updated_at FROM games WHERE status='published' AND type='game' ORDER BY id") as $g)
    echo $row($rel(game_url($g)), $g['updated_at'], '0.6');
foreach (['about', 'dmca', 'disclaimer', 'privacy', 'contact'] as $p) echo $row(abs_url("$p/"), null, '0.2');
echo "</urlset>\n";
