<?php
// /download-ipastore/ — landing page for the IPAStore web app (installed as a Web Clip profile).
$app   = cfg('ipastore_app');
$file  = ipastore_profile();
$total = games_count('1=1');

// Edit these to match what the app actually does.
$features = [
    ['▦', number_format($total) . '+ games', 'Browse every game in the store by genre, from racing and puzzle to offline games.'],
    ['✦', 'Always the latest version', 'See new releases and updates first, with version numbers and changelogs.'],
    ['⚡', 'Installs in seconds', 'No App Store, no computer and no Apple ID. One profile adds IPAStore to your Home Screen.'],
    ['⛶', 'Full-screen app', 'Opens full screen from its own Home Screen icon, just like a native app.'],
    ['◎', 'Built-in install guides', 'Step-by-step help for AltStore, SideStore and fixing failed installs.'],
    ['☾', 'Made for the dark', 'The same cinematic look as the website, on iPhone and iPad.'],
];

$faq = [
    ['Is IPAStore free?', 'Yes. IPAStore is free to download and use.'],
    ['What does the IPAStore profile do?', 'It adds one thing: an IPAStore icon on your Home Screen that opens ' . e($app['opens']) . ' full screen. It is a Web Clip profile only. It does not include device management (MDM), certificates, VPN or any other settings.'],
    ['Do I need a computer, an Apple ID or a jailbreak?', 'No. You install it from Safari on your iPhone or iPad in a few taps.'],
    ['Why does iOS show a "profile" screen?', 'iOS installs Home Screen web apps from websites through a configuration profile. The install screen shows exactly what the profile contains. For IPAStore that is a single Web Clip.'],
    ['How do I remove IPAStore?', 'Go to <em>Settings › General › VPN &amp; Device Management</em>, tap <strong>IPA Game</strong> and choose <strong>Remove Profile</strong>. The Home Screen icon disappears with it.'],
    ['Does IPAStore work on iPad?', 'Yes. It works on iPhone and iPad with iOS ' . e($app['min_ios']) . ' or later.'],
];

$crumbs = [['Home', url()], ['Download IPAStore', url('download-ipastore/')]];

render('ipastore', compact('app', 'file', 'total', 'features', 'faq', 'crumbs'), [
    'title'       => 'Download IPAStore – IPA Store App for iPhone & iPad',
    'description' => "Download the IPAStore app for iPhone and iPad. Browse $total+ IPA games and get the latest versions, straight from your Home Screen. Free, installs in seconds.",
    'canonical'   => abs_url('download-ipastore/'),
    'body_class'  => 'is-app',
    'nav'         => 'app',
    'schema'      => [
        breadcrumb_ld($crumbs),
        faq_ld($faq),
        ['@context' => 'https://schema.org', '@type' => 'WebApplication', 'name' => 'IPAStore',
         'url' => 'https://' . $app['opens'] . '/', 'operatingSystem' => "iOS {$app['min_ios']}+",
         'applicationCategory' => 'UtilitiesApplication', 'softwareVersion' => $app['version'],
         'image' => abs_url('assets/img/logo-512.png'),
         'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD']],
    ],
]);
