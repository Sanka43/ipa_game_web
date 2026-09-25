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
];
