<?php
/** @var string $catSlug  @var string $gameSlug */
$g = game_by_slug($gameSlug) ?? not_found();
if ($g['category'] !== $catSlug) redirect(game_url($g) . 'download/');

$file    = ipa_file($g);
$cat     = category($g['category']);
$shots   = game_screenshots((int) $g['id'], 'ipad', 1) ?: game_screenshots((int) $g['id'], 'iphone', 1);
$similar = similar_games($g, 6);
$name    = $g['name'];
$ver     = version_label($g['latest_version']);
$size    = size_label($file['size_mb'] ?? $g['latest_size_mb']);
$ios     = $g['min_ios'] ? "iOS {$g['min_ios']}" : '';
$devs    = implode(' & ', array_filter([$g['is_iphone'] ? 'iPhone' : '', $g['is_ipad'] ? 'iPad' : '']));
$crumbs  = [['Home', url()], ['IPA Games', url('ipa-games/')], [$cat['h1'], category_url($g['category'])],
            ["$name IPA", game_url($g)], ['Download', game_url($g) . 'download/']];

render('download', compact('g', 'file', 'cat', 'shots', 'similar', 'name', 'ver', 'size', 'ios', 'devs', 'crumbs'), [
    'title'       => "Download $name IPA" . ($ver ? " $ver" : '') . ' – Direct IPA File',
    'description' => excerpt("Download the $name IPA file ($ver, $size) for iPhone and iPad. File details, SHA-256 checksum and step-by-step install instructions.", 158),
    'canonical'   => abs_url("ipa-games/{$g['category']}/{$g['slug']}-ipa/download/"),
    // The game page is the page we want ranked; this one only hands out the file.
    'robots'      => 'noindex, follow',
    'og_image'    => img($g['icon'], '1200x630'),
    'nav'         => 'games',
    'body_class'  => 'is-download',
    'schema'      => [breadcrumb_ld($crumbs)],
]);
