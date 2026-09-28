<?php
// Shown when someone tries to download the IPA Game Store profile from a computer or Android phone.
// Domain shown in the steps. Locally SITE_URL is localhost, so fall back to the live domain.
$host = parse_url(SITE_URL, PHP_URL_HOST) ?: '';
if ($host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) $host = 'ipagame.store';
$crumbs  = [['Home', url()], ['Download IPA Game Store', url('download-ipastore/')], ['Open on iPhone', url('download-ipastore/open-on-iphone/')]];

render('ipastore-device', compact('host', 'crumbs'), [
    'title'       => 'Open IPA Game Store on your iPhone or iPad',
    'description' => 'IPA Game Store installs from Safari on iPhone and iPad. Open this page in Safari on your iPhone or iPad to continue.',
    'canonical'   => abs_url('download-ipastore/open-on-iphone/'),
    'robots'      => 'noindex, follow',
    'body_class'  => 'is-app',
    'nav'         => 'app',
]);
