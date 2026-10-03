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

$name  = game_name($g);
$ver   = version_label($g['latest_version']);
$size  = size_label($g['latest_size_mb']);
$ios   = $g['min_ios'] ? "iOS {$g['min_ios']}" : '';
$devs  = implode(' & ', array_filter([$g['is_iphone'] ? 'iPhone' : '', $g['is_ipad'] ? 'iPad' : '']));
$free  = (float) $g['price'] <= 0;
$hasFile = $free && ipa_file($g) !== null;
// IPA vs App Store, same test as the admin: some version links off the App Store.
$isIpa = (bool) array_filter($versions, fn($v) => $v['download_url'] !== '' && !str_contains($v['download_url'], 'apps.apple.com'));

// Paid games are only offered through their App Store listing.
$storeHref = $g['app_store_url'] ? url("out/{$g['slug']}/") : null;
$getHref   = !$free && $storeHref ? $storeHref : game_url($g) . 'download/';
$getLabel  = !$free && $storeHref ? 'Get on the App Store' : 'Get on IPA Game Store';

// Guides that fit this game: always the basics, then iPad / verification / sideloading help by game type.
$guideSlugs = array_values(array_unique(array_filter([
    'how-to-install-ipa-on-iphone',
    $g['is_ipad'] ? 'install-ipa-on-ipad' : 'install-ipa-without-computer',
    $isIpa ? 'how-to-verify-ipa-file' : 'are-ipa-files-safe',
    'ipa-installation-failed',
], fn($s) => guide($s))));

$crumbs = [['Home', url()], ['IPA Games', url('ipa-games/')], [$cat['h1'], category_url($g['category'])], ["$name IPA", game_url($g)]];

$n = e($name);
$faq = [
    ["How do I get $name on iPhone?", !$free && $storeHref
        ? "$n is a paid game. Buy and install it from its <a href=\"$storeHref\" rel=\"nofollow noopener\" target=\"_blank\">App Store listing</a>. Updates then arrive automatically."
        : "Tap <strong>Get on IPA Game Store</strong> to open the <a href=\"" . game_url($g) . "download/\">$n download page</a>, then add the free IPA Game Store app to your Home Screen and find $n there." . ($storeHref ? " $n is also on the <a href=\"$storeHref\" rel=\"nofollow noopener\" target=\"_blank\">App Store</a>." : '') . " To sideload an IPA yourself, see our <a href=\"" . guide_url('how-to-install-ipa-on-iphone') . "\">install guide</a>."],
    ["Is $name free?", $free ? "Yes, $n is free to download. It may offer optional in-app purchases." : "No. $n is a paid game on the App Store."],
    ["What iOS version does $name need?", $ios ? "$n $ver needs $ios or later. It runs on $devs." : "Check the App Store listing for current requirements."],
    ["How big is $name?", "The $ver download is about $size. Many games download more content the first time you open them, so leave some extra free space."],
    ["Does $name work on iPad?", $g['is_ipad'] ? "Yes. $n supports iPad natively" . ($ipadShots ? ', with a layout made for the larger screen.' : '.') : "$n is built for iPhone. It can still run on iPad in iPhone compatibility mode."],
];
if ($g['is_offline']) $faq[] = ["Can I play $name offline?", "Yes. The developer says $n supports offline play. You may need to connect once to download content on first launch."];

$metaDesc = excerpt("$name $ver for iPhone and iPad: $size" . ($ios ? ", $ios or later" : '') . ". Screenshots, what's new, install guide, safety info and full update history.", 158);

// Keep the title within ~60 characters: the version (and then the brand suffix) drop off first.
$title = $name . ($isIpa ? ' IPA' : '') . ' for iPhone & iPad';
if ($ver && mb_strlen("$title ($ver) | " . SITE_NAME) <= 60) $title .= " ($ver)";

// Admin overrides (games.seo_title / seo_description / seo_noindex) win over the generated values.
if (trim((string) $g['seo_title']) !== '') $title = trim($g['seo_title']);
if (trim((string) $g['seo_description']) !== '') $metaDesc = trim($g['seo_description']);

render('game', compact('g', 'shots', 'ipadShots', 'versions', 'similar', 'cat', 'latest', 'name', 'ver', 'size', 'ios', 'devs', 'free', 'crumbs', 'faq', 'storeHref', 'getHref', 'getLabel', 'hasFile', 'isIpa', 'guideSlugs'), [
    'title'       => $title,
    'description' => $metaDesc,
    'canonical'   => abs_url("ipa-games/{$g['category']}/{$g['slug']}-ipa/"),
    'robots'      => !empty($g['seo_noindex']) ? 'noindex, follow' : null,
    'og_image'    => img($g['icon'], '1200x630'),
    'nav'         => 'games',
    'body_class'  => 'is-game',
    'preload'     => img($ipadShots[0] ?? $shots[0] ?? $g['icon'], '1400x0w'),
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
        ]),
        breadcrumb_ld($crumbs),
    ],
]);
