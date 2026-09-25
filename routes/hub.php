<?php
// /ipa-games/ — the top of the category silo.
$per   = cfg('per_page');
$page  = current_page();
$total = games_count('1=1');
$pages = (int) ceil($total / $per);
if ($page > max(1, $pages)) not_found();

$games   = games_where('1=1', 'rating_count DESC', $per, ($page - 1) * $per);
$counts  = genre_counts();
$posters = genre_posters(genres());
$base    = url('ipa-games/');
$crumbs  = [['Home', url()], ['IPA Games', $base]];

$faq = [
    ['Where can I download IPA games?', 'Every game page on this site has a download button. Free games point to the official App Store listing, which is the safest way to install them. Our <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">install guide</a> covers sideloading an IPA file yourself.'],
    ['What are the most popular iOS games?', 'This page ranks games by their number of ratings. The <a href="' . category_url('iphone') . '">iPhone chart</a> has the full ranking.'],
    ['Which IPA games work offline?', 'We tag every game that supports offline play. You will find them in <a href="' . category_url('offline') . '">offline IPA games</a>.'],
    ['How often are new IPA games added?', 'New games and updates are added every day. The <a href="' . category_url('latest') . '">latest IPA games</a> page lists them by release date.'],
];

render('hub', compact('games', 'counts', 'posters', 'page', 'pages', 'total', 'base', 'crumbs', 'faq'), [
    'title'       => ($page > 1 ? "IPA Games – Page $page" : 'IPA Games Download – iOS Games IPA for iPhone & iPad'),
    'description' => "Download IPA games for iPhone and iPad. Browse " . number_format($total) . " iOS games IPA by category: action, racing, puzzle, offline and more.",
    'canonical'   => abs_url('ipa-games/' . ($page > 1 ? "?page=$page" : '')),
    'nav'         => 'games',
    'schema'      => [breadcrumb_ld($crumbs), faq_ld($faq), [
        '@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => 'IPA Games', 'url' => abs_url('ipa-games/'),
    ]],
]);
