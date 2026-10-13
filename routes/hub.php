<?php
// /ipa-games/ and /ipa-apps/ — the top of the category silo. $kind is 'game' or 'app' (index.php).
$isApp = $kind === 'app';
$Noun  = $isApp ? 'Apps' : 'Games';
$noun  = strtolower($Noun);
$per   = cfg('per_page');
$page  = current_page();
$total = games_count('1=1', $kind);
$pages = (int) ceil($total / $per);
if ($page > max(1, $pages)) not_found();

$games   = games_where('1=1', 'rating_count DESC', $per, ($page - 1) * $per, $kind);
$counts  = genre_counts($kind);
$posters = genre_posters($isApp ? app_genres() : genres(), $kind);
$base    = url(section($kind) . '/');
$crumbs  = [['Home', url()], ["IPA $Noun", $base]];

$faq = $isApp ? [
    ['Where can I download IPA apps?', 'Every app page on this site has a download button. Free apps point to the official App Store listing, which is the safest way to install them. Our <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">install guide</a> covers sideloading an IPA file yourself.'],
    ['What are the most popular iOS apps?', 'This page ranks apps by their number of ratings.'],
    ['How often are new IPA apps added?', 'New apps and updates are added every day. The <a href="' . category_url('latest', 'app') . '">latest IPA apps</a> page lists them by release date.'],
] : [
    ['Where can I download IPA games?', 'Every game page on this site has a download button. Free games point to the official App Store listing, which is the safest way to install them. Our <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">install guide</a> covers sideloading an IPA file yourself.'],
    ['What are the most popular iOS games?', 'This page ranks games by their number of ratings. The <a href="' . category_url('iphone') . '">iPhone chart</a> has the full ranking.'],
    ['Which IPA games work offline?', 'We tag every game that supports offline play. You will find them in <a href="' . category_url('offline') . '">offline IPA games</a>.'],
    ['How often are new IPA games added?', 'New games and updates are added every day. The <a href="' . category_url('latest') . '">latest IPA games</a> page lists them by release date.'],
];

render('hub', compact('kind', 'games', 'counts', 'posters', 'page', 'pages', 'total', 'base', 'crumbs', 'faq'), [
    'title'       => $page > 1 ? "IPA $Noun – Page $page" : ($isApp ? 'IPA Apps Download – iOS Apps IPA for iPhone & iPad' : 'IPA Games Download – iOS Games IPA for iPhone & iPad'),
    'description' => ($page > 1 ? "Page $page of $pages. " : '') . ($isApp
        ? 'IPA apps for iPhone and iPad. Browse ' . number_format($total) . ' iOS apps IPA by category: productivity, utilities, photo & video and more.'
        : 'IPA games for iPhone and iPad. Browse ' . number_format($total) . ' iOS games IPA by category: action, racing, puzzle, offline and more.'),
    'canonical'   => abs_url(section($kind) . '/' . ($page > 1 ? "?page=$page" : '')),
    'nav'         => $isApp ? 'apps' : 'games',
    'schema'      => [breadcrumb_ld($crumbs), [
        '@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => "IPA $Noun", 'url' => abs_url(section($kind) . '/'),
    ]],
]);
