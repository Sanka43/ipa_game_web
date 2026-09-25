<?php
// Shown when someone tries to download the IPAStore profile from a computer or Android phone.
$pageUrl = abs_url('download-ipastore/');
$crumbs  = [['Home', url()], ['Download IPAStore', url('download-ipastore/')], ['Open on iPhone', url('download-ipastore/open-on-iphone/')]];

render('ipastore-device', compact('pageUrl', 'crumbs'), [
    'title'       => 'Open IPAStore on your iPhone or iPad',
    'description' => 'IPAStore installs from Safari on iPhone and iPad. Scan the QR code with your iPhone camera to continue.',
    'canonical'   => abs_url('download-ipastore/open-on-iphone/'),
    'robots'      => 'noindex, follow',
    'body_class'  => 'is-app',
    'nav'         => 'app',
    'scripts'     => ['https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js'],
]);
