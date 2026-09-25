<?php
// Every indexable URL, shared by sitemap.xml and the HTML sitemap page.
// Each entry: [absolute url, title, section, lastmod|null, priority].
$rel = fn($u) => SITE_URL . substr($u, strlen(str_replace(' ', '%20', BASE_PATH)));
$today = date('Y-m-d');

$entries = [[abs_url(), 'Home', 'Pages', $today, '1.0'], [abs_url('ipa-games/'), 'All IPA Games', 'Categories', $today, '0.9']];
foreach (array_keys(categories()) as $s)
    if (games_count(($c = category($s))['where']) > 0) $entries[] = [abs_url("ipa-games/$s/"), $c['h1'], 'Categories', $today, '0.8'];
$entries[] = [abs_url('download-ipastore/'), 'Download IPAStore', 'Pages', null, '0.9'];
$entries[] = [abs_url('guides/'), 'All Guides', 'Guides', null, '0.8'];
foreach (guides() as $s => $g) $entries[] = [abs_url("guides/$s/"), $g['short'], 'Guides', null, '0.8'];
foreach (db()->query("SELECT slug, name, category, updated_at FROM games WHERE status='published' AND type='game' ORDER BY id") as $g)
    $entries[] = [$rel(game_url($g)), preg_replace('/(?<! IPA)$/', ' IPA', $g['name']), 'Games', date('Y-m-d', strtotime($g['updated_at'])), '0.6'];
foreach (['about' => 'About', 'dmca' => 'DMCA', 'disclaimer' => 'Disclaimer', 'privacy' => 'Privacy Policy', 'contact' => 'Contact'] as $p => $t)
    $entries[] = [abs_url("$p/"), $t, 'Pages', null, '0.2'];

return $entries;
