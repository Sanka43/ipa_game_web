<?php
// Copy to config.live.php ON THE SERVER ONLY and fill in the cPanel MySQL details.
// cPanel prefixes database and user names with your account name, e.g. 'cpuser_ipa_store'.
return [
    'db' => [
        'host' => 'localhost',
        'name' => 'CPANELUSER_ipa_store',
        'user' => 'CPANELUSER_ipauser',
        'pass' => 'YOUR_DB_PASSWORD',
    ],
    'site_url' => 'https://ipagame.store',
    'debug'    => false,   // hide error details from visitors
    // Access key for /seo-health/ (pick a long random string; keep it out of git).
    'seo_health_key' => 'CHANGE_ME_TO_A_LONG_RANDOM_STRING',
    // Social links in the home hero (empty string hides a button).
    'social' => [
        'telegram' => 'https://t.me/YOUR_CHANNEL',
        'x'        => 'https://x.com/YOUR_HANDLE',
        'youtube'  => 'https://www.youtube.com/@YOUR_CHANNEL',
    ],
];
