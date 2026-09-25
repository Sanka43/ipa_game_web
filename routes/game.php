<?php
/** @var string $catSlug  @var string $gameSlug */
$g = game_by_slug($gameSlug) ?? not_found();
if ($g['category'] !== $catSlug) redirect(game_url($g));          // one URL per game

$id        = (int) $g['id'];
$shots     = game_screenshots($id, 'iphone');
$ipadShots = game_screenshots($id, 'ipad');
$versions  = game_versions($id);
$similar   = similar_games($g);
$cat       = category($g['category']);
$latest    = $versions[0] ?? null;

$name  = $g['name'];
$ver   = version_label($g['latest_version']);
$size  = size_label($g['latest_size_mb']);
$ios   = $g['min_ios'] ? "iOS {$g['min_ios']}" : '';
$devs  = implode(' & ', array_filter([$g['is_iphone'] ? 'iPhone' : '', $g['is_ipad'] ? 'iPad' : '']));
$free  = (float) $g['price'] <= 0;

$crumbs = [['Home', url()], ['IPA Games', url('ipa-games/')], [$cat['h1'], category_url($g['category'])], ["$name IPA", game_url($g)]];

$faq = [
    ["Where can I download the $name IPA file?", "Tap <strong>Get on IPAStore</strong> to open the <a href=\"" . game_url($g) . "download/\">$name IPA download page</a>. It lists the version, the file size and the SHA-256 checksum. Then install it with AltStore or SideStore, as shown in our <a href=\"" . guide_url('how-to-install-ipa-on-iphone') . "\">sideloading guide</a>."],
    ["Is $name free?", $free ? "Yes, $name is free to download. It may offer optional in-app purchases." : "$name is a paid game on the App Store."],
    ["What iOS version does $name need?", $ios ? "$name $ver needs $ios or later. It runs on $devs." : "Check the App Store listing for current requirements."],
    ["How big is the $name IPA?", "The $ver download is about $size. Many games download more content the first time you open them, so leave some extra free space."],
    ["Does $name work on iPad?", $g['is_ipad'] ? "Yes. $name supports iPad natively" . ($ipadShots ? ', with a layout made for the larger screen.' : '.') : "$name is built for iPhone. It can still run on iPad in iPhone compatibility mode."],
];
if ($g['is_offline']) $faq[] = ["Can I play $name offline?", "Yes. The developer says $name supports offline play. You may need to connect once to download content on first launch."];

$metaDesc = excerpt("Download $name IPA $ver for iPhone and iPad. $size, requires " . ($ios ?: 'a recent iOS version') . ". Screenshots, what's new, install steps, safety info and full update history.", 158);

render('game', compact('g', 'shots', 'ipadShots', 'versions', 'similar', 'cat', 'latest', 'name', 'ver', 'size', 'ios', 'devs', 'free', 'crumbs', 'faq'), [
    'title'       => "$name IPA Download for iPhone & iPad" . ($ver ? " ($ver)" : ''),
    'description' => $metaDesc,
    'canonical'   => abs_url("ipa-games/{$g['category']}/{$g['slug']}-ipa/"),
    'og_image'    => img($g['icon'], '1200x630'),
    'og_type'     => 'article',
    'nav'         => 'games',
    'body_class'  => 'is-game',
    'schema'      => [
        array_filter([
            '@context' => 'https://schema.org', '@type' => 'SoftwareApplication',
            'name' => $name, 'operatingSystem' => 'iOS' . ($g['min_ios'] ? " {$g['min_ios']}+" : ''),
            'applicationCategory' => 'GameApplication', 'applicationSubCategory' => $cat['name'],
            'softwareVersion' => ltrim((string) $g['latest_version'], 'vV'), 'fileSize' => $size,
            'datePublished' => $g['latest_release_date'], 'contentRating' => $g['content_rating'],
            'image' => img($g['icon'], '512x512'), 'screenshot' => array_map(fn($s) => img($s, '600x0w'), array_slice($shots, 0, 4)),
            'author' => ['@type' => 'Organization', 'name' => $g['developer']],
            'offers' => ['@type' => 'Offer', 'price' => number_format((float) $g['price'], 2, '.', ''), 'priceCurrency' => 'USD'],
            'aggregateRating' => $g['rating_count'] > 0 ? ['@type' => 'AggregateRating', 'ratingValue' => (float) $g['rating_value'], 'ratingCount' => (int) $g['rating_count'], 'bestRating' => 5] : null,
        ]),
        breadcrumb_ld($crumbs),
        faq_ld($faq),
    ],
]);
