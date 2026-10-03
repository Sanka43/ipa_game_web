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
    'site_name' => 'IPA Game Store',
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
    // Social links shown in the home hero. Empty = button hidden. '#' = placeholder.
    'social' => [
        'telegram' => '#',
        'x'        => '#',
        'youtube'  => '#',
    ],
    // Google Analytics 4 measurement ID, e.g. 'G-XXXXXXXXXX'. Empty = no tracking.
    // Only loaded when debug is off, so local XAMPP visits aren't counted.
    'ga_id'     => 'G-6K03K5ZMR9',
    // Microsoft Clarity project ID (Settings > Overview). Empty = no tracking. Also skipped in debug.
    'clarity_id' => 'yp6w3pnjbv',
    // Access key for the private /seo-health/ report. Empty = the page does not exist. Set it in config.live.php.
    'seo_health_key' => '',
    // Search Console API for /seo-health/ (set in config.live.php). key_file: service-account JSON, path relative to the site root
    // or absolute (keep it in app/, which the web server blocks). site: 'sc-domain:ipagame.store' (Domain property) or 'https://ipagame.store/'.
    'gsc' => ['key_file' => '', 'site' => ''],
    'debug'     => true,
];

if (is_file(__DIR__ . '/config.live.php')) {
    $config = array_replace_recursive($config, require __DIR__ . '/config.live.php');
}
return $config;
