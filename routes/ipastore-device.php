<?php
// Shown when someone tries to download the IPAStore profile from a computer or Android phone.
// Domain shown in the steps. Locally SITE_URL is localhost, so fall back to the live domain.
$host = parse_url(SITE_URL, PHP_URL_HOST) ?: '';
if ($host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) $host = 'ipagame.store';
$crumbs  = [['Home', url()], ['Download IPAStore', url('download-ipastore/')], ['Open on iPhone', url('download-ipastore/open-on-iphone/')]];

render('ipastore-device', compact('host', 'crumbs'), [
    'title'       => 'Open IPAStore on your iPhone or iPad',
    'description' => 'IPAStore installs from Safari on iPhone and iPad. Open this page in Safari on your iPhone or iPad to continue.',
    'canonical'   => abs_url('download-ipastore/open-on-iphone/'),
    'robots'      => 'noindex, follow',
    'body_class'  => 'is-app',
    'nav'         => 'app',
]);
