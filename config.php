<?php
// Site configuration. Defaults below are for local XAMPP.
// On the live host, create config.live.php (copy config.live.example.php) with the
// cPanel database details; it overrides these values and is never overwritten by uploads.
$config = [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'ipa_store',
        'user' => 'root',
        'pass' => '',
    ],
    // Full public URL without trailing slash, e.g. 'https://ipastore.com'.
    // Empty = auto-detect (fine for localhost).
    'site_url'  => '',
    'site_name' => 'IPA Store',
    'tagline'   => 'Free IPA Games for iPhone & iPad',
    'per_page'  => 24,
    // The IPAStore web app on /download-ipastore/: a signed Web Clip profile that
    // adds an icon for 'opens' to the Home Screen. Replace the file to update it.
    'ipastore_app' => [
        'version' => '1.0',
        'min_ios' => '15.0',
        'file'    => 'downloads/ipastore.mobileconfig',
        'opens'   => 'app.ipagame.store',
    ],
    'debug'     => true,
];

if (is_file(__DIR__ . '/config.live.php')) {
    $config = array_replace_recursive($config, require __DIR__ . '/config.live.php');
}
return $config;
