<?php
$hero    = hero_games(5);
$latest  = games_where('1=1', 'latest_release_date DESC, id DESC', 14);
$shots   = first_screenshots(array_column($latest, 'id'));
foreach ($latest as &$g) $g['shot'] = $shots[$g['id']] ?? '';
unset($g);
$top     = top_downloaded(10);
$shelves = [
    'offline' => games_where('is_offline=1', 'rating_count DESC', 6),
    'racing'  => games_where("category='racing'", 'rating_count DESC', 6),
    'puzzle'  => games_where("category='puzzle'", 'rating_count DESC', 6),
];
$posterGenres = ['action', 'racing', 'puzzle', 'adventure', 'role-playing', 'simulation', 'strategy', 'sports'];
$posters = genre_posters($posterGenres);
$counts  = genre_counts();
$total   = games_count('1=1');
$latestDate = !empty($latest[0]['latest_release_date']) ? date_label($latest[0]['latest_release_date']) : date('M j, Y');

$allGuides = guides();
$featuredGuideKeys = [
    'how-to-install-ipa-on-iphone',
    'install-ipa-with-livecontainer',
    'install-ipa-without-computer',
    'install-ipa-with-sidestore',
    'are-ipa-files-safe',
    'ipa-installation-failed',
];
$featuredGuides = [];
foreach ($featuredGuideKeys as $key) {
    if (isset($allGuides[$key])) {
        $featuredGuides[$key] = $allGuides[$key];
    }
}

$faq = [
    ['What is an IPA file?', 'An IPA file is the package format for iOS apps and games. It is a ZIP archive that holds the compiled app, its images and its code signature. <a href="' . guide_url('what-is-an-ipa-file') . '">Read the full explanation</a>.'],
    ['How do I install IPA games on iPhone?', 'Download the game, then use a signing tool such as the IPA Game Store app, LiveContainer, SideStore or AltStore with your Apple ID to install it. Games that are on the App Store install directly from there. <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">See every install method</a>.'],
    ['Are the IPA games free?', 'Every game listed here is free to download. Some offer optional in-app purchases, and the game page tells you when a game uses them.'],
    ['Do I need to jailbreak my iPhone?', 'No. Sideloading with the IPA Game Store app, LiveContainer, SideStore or AltStore works on a normal, non-jailbroken iPhone or iPad.'],
    ['Can I install IPA games without a computer?', 'Yes, after a one-time setup. SideStore and LiveContainer can install and refresh apps directly on your iPhone without needing a computer. <a href="' . guide_url('install-ipa-without-computer') . '">See how</a>.'],
    ['Can I bypass the 3-app sideloading limit?', 'Yes. By using LiveContainer combined with SideStore, you can bypass the standard free Apple ID 3-app limit and run unlimited sideloaded apps and games on your device. Follow our <a href="' . guide_url('install-ipa-with-livecontainer') . '">LiveContainer setup guide</a> for step-by-step instructions.'],
    ['Do IPA games work on iPad?', 'Almost every game in the store supports iPad natively. The <a href="' . category_url('ipad') . '">iPad games</a> list shows only titles built for the larger screen. <a href="' . guide_url('install-ipa-on-ipad') . '">Install IPA on iPad</a>.'],
    ['Is it safe to download IPA files?', 'It depends on the source. Official App Store links are the safest. For other IPA files, check the checksum and the signature first. <a href="' . guide_url('are-ipa-files-safe') . '">Read our safety guide</a>.'],
    ['Which iOS versions are supported?', 'Each game page lists its minimum iOS version. Our catalog supports iOS 15 through iOS 26. The IPA Game Store app itself runs smoothly on iOS 15 and later versions.'],
    ['Why does my IPA install fail or expire after 7 days?', 'Free Apple IDs sign apps for 7 days, so they must be refreshed. SideStore and AltStore can refresh automatically over Wi-Fi. For other errors see <a href="' . guide_url('ipa-installation-failed') . '">IPA installation failed: 12 fixes</a>.'],
    ['Can I play IPA games offline?', 'Many can. Open the <a href="' . category_url('offline') . '">offline IPA games</a> list to see games that work without Wi-Fi or mobile data.'],
    ['What is the difference between an IPA file and an App Store app?', 'App Store apps are installed and updated by Apple. IPA files are the same app package, but you install it yourself. <a href="' . guide_url('ipa-vs-app-store') . '">IPA vs App Store</a>.'],
];

$faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(
    fn($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f[1]))]],
    $faq
)];

render('home', compact('hero', 'latest', 'top', 'shelves', 'posters', 'posterGenres', 'counts', 'total', 'faq', 'latestDate', 'featuredGuides'), [
    'title'      => SITE_NAME . ' – Free IPA Games for iPhone & iPad',
    'description'=> "Download free IPA games for iPhone & iPad (iOS 15–26). " . number_format($total) . "+ iOS games with version history, file sizes, iOS requirements, screenshots and easy install guides. No jailbreak needed.",
    'canonical'  => abs_url(),
    'body_class' => 'is-home',
    'preload'    => !empty($hero[0]) ? img($hero[0]['shots'][0] ?? $hero[0]['icon'], '1400x0w') : null,
    'schema'     => [
        ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => SITE_NAME, 'alternateName' => ['IPA Game', 'ipagame.store', 'Jogos IPA', 'Juegos IPA', 'IPA Games Download', 'IPA Files'], 'url' => abs_url(),
         'potentialAction' => ['@type' => 'SearchAction', 'target' => abs_url('search/') . '?q={search_term_string}', 'query-input' => 'required name=search_term_string']],
        ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => SITE_NAME, 'url' => abs_url(), 'logo' => abs_url('assets/img/logo-512.png'),
         'sameAs' => array_values(array_filter(array_map('strval', cfg('social') ?? []), fn($u) => str_starts_with($u, 'http'))),
         'contactPoint' => ['@type' => 'ContactPoint', 'contactType' => 'customer support', 'email' => 'info@ipagame.store']],
        ['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'Top 10 Most Downloaded IPA Games',
         'itemListElement' => array_map(fn($g, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $g['name'], 'url' => abs_url("ipa-games/{$g['category']}/{$g['slug']}-ipa/")], $top, array_keys($top)),
        ],
        $faqLd,
    ],
]);
