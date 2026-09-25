<?php
$hero    = hero_games(5);
$latest  = games_where('1=1', 'latest_release_date DESC, id DESC', 14);
foreach ($latest as &$g) $g['shot'] = game_screenshots((int) $g['id'], 'iphone', 1)[0] ?? '';
unset($g);
$top     = games_where('1=1', 'rating_count DESC', 10);
$shelves = [
    'offline' => games_where('is_offline=1', 'rating_count DESC', 6),
    'racing'  => games_where("category='racing'", 'rating_count DESC', 6),
    'puzzle'  => games_where("category='puzzle'", 'rating_count DESC', 6),
];
$posterGenres = ['action', 'racing', 'puzzle', 'adventure', 'role-playing', 'simulation', 'strategy', 'sports'];
$posters = genre_posters($posterGenres);
$counts  = genre_counts();
$total   = games_count('1=1');

$faq = [
    ['What is an IPA file?', 'An IPA file is the package format for iOS apps and games. It is a ZIP archive that holds the compiled app, its images and its code signature. <a href="' . guide_url('what-is-an-ipa-file') . '">Read the full explanation</a>.'],
    ['How do I install IPA games on iPhone?', 'Most games here install directly from the App Store. To sideload an IPA file, use a signing tool such as AltStore or SideStore with your Apple ID. <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">See every install method</a>.'],
    ['Are the IPA games free?', 'Every game listed here is free to download. Some offer optional in-app purchases, and the game page tells you when a game uses them.'],
    ['Can I install IPA games without a computer?', 'Yes, after a one-time setup. SideStore can install and refresh apps on the iPhone itself once it has been paired. <a href="' . guide_url('install-ipa-without-computer') . '">See how</a>.'],
    ['Do IPA games work on iPad?', 'Almost every game in the store supports iPad natively. The <a href="' . category_url('ipad') . '">iPad games</a> list shows only titles built for the larger screen.'],
    ['Is it safe to download IPA files?', 'It depends on the source. Official App Store links are the safest. For other IPA files, check the checksum and the signature first. <a href="' . guide_url('are-ipa-files-safe') . '">Read our safety guide</a>.'],
];

render('home', compact('hero', 'latest', 'top', 'shelves', 'posters', 'posterGenres', 'counts', 'total', 'faq'), [
    'title'      => 'IPA Store – Free IPA Games Download for iPhone & iPad',
    'description'=> "Download free IPA games for iPhone and iPad. $total+ iOS games with versions, file sizes, screenshots and step-by-step IPA install guides.",
    'canonical'  => abs_url(),
    'body_class' => 'is-home',
    'schema'     => [
        ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => SITE_NAME, 'url' => abs_url(),
         'potentialAction' => ['@type' => 'SearchAction', 'target' => abs_url('search/') . '?q={q}', 'query-input' => 'required name=q']],
        ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => SITE_NAME, 'url' => abs_url(), 'logo' => abs_url('assets/img/logo-512.png')],
        faq_ld($faq),
    ],
]);
