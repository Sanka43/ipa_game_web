<?php
$related = ['how-to-install-ipa-on-iphone', 'sideload-games-on-iphone', 'ipa-installation-failed'];
$howto = [
    ['Check iPadOS and storage', 'Open Settings > General > About to see your iPadOS version, and make sure you have free storage.'],
    ['Install a signing tool', 'Set up AltStore or SideStore on the iPad the same way you would on an iPhone.'],
    ['Trust the developer', 'Go to Settings > General > VPN & Device Management and trust your Apple ID.'],
    ['Enable Developer Mode', 'On iPadOS 16 or later, turn on Settings > Privacy & Security > Developer Mode and restart.'],
    ['Install the IPA', 'In your signing tool, open My Apps, tap + and select the IPA from Files.'],
];
$faq = [
    ['Can iPhone IPA files run on iPad?', 'Usually yes. Apps built for iPhone only run on iPad in compatibility mode, in a phone-sized window that you can scale up.'],
    ['Do sideloaded apps count against the iPhone\'s 3-app limit?', 'The limit applies to each Apple ID and each device. The iPad has its own 3 active slots, but the 10 App IDs per week are shared across all devices on that Apple ID.'],
    ['Do I need a different IPA for iPad?', 'No. Most IPAs are universal and include both the iPhone and iPad layouts in one file.'],
];
?>
<p>Installing an IPA on an iPad works <strong>exactly the same way</strong> as on an iPhone. iPadOS uses the same signing rules. There are a few iPad-specific details, though, and this guide covers them.</p>

<h2>Universal vs iPhone-only IPAs</h2>
<p>Most games ship as a <strong>universal</strong> IPA. One file contains layouts for both iPhone and iPad, and iPadOS picks the right one. An iPhone-only IPA still installs on an iPad, but it runs in <strong>compatibility mode</strong>: a phone-shaped window that you can scale to 2×. It works fine, but it looks softer. Every game in our <a href="<?= category_url('ipad') ?>">IPA games for iPad</a> list supports iPad natively.</p>

<h2>Step-by-step on iPad</h2>
<ol class="steps">
  <li><strong>Pick a method.</strong> <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> (computer on the same Wi-Fi) or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a> (computer once). Setup is the same as on iPhone.</li>
  <li><strong>Connect the iPad.</strong> Newer iPads use USB-C, so use a USB-C cable, or a USB-C to USB-A cable for older computers.</li>
  <li><strong>Trust:</strong> <em>Settings › General › VPN &amp; Device Management</em> › your Apple ID › Trust.</li>
  <li><strong>Developer Mode</strong> (iPadOS 16+): <em>Settings › Privacy &amp; Security › Developer Mode</em>.</li>
  <li><strong>Install:</strong> open the tool › <em>My Apps</em> › <strong>+</strong> › pick the IPA from Files.</li>
</ol>

<h2>iPad-specific tips</h2>
<ul>
  <li><strong>Storage:</strong> big games (racing, open-world) can grow to several GB after first launch. Check the size on each game page before you install.</li>
  <li><strong>Files app:</strong> on iPad you can drag an IPA from a USB drive or SMB share into Files, then open it in AltStore or SideStore.</li>
  <li><strong>Stage Manager:</strong> most games run full-screen, but compatibility-mode apps can sit in a small window next to others.</li>
  <li><strong>Controllers:</strong> iPadOS supports Xbox, PlayStation and MFi controllers. Pair them in <em>Settings › Bluetooth</em>.</li>
  <li><strong>Keyboard &amp; mouse:</strong> some games support a Magic Keyboard or trackpad directly.</li>
</ul>

<h2>Shared Apple ID limits</h2>
<p>If you sideload on both an iPhone and an iPad with the same free Apple ID, each device has its own 3 active app slots. However, both share the <strong>10 new App IDs per 7 days</strong> limit, so installing many different apps across devices uses it up quickly.</p>
<p>Problems? See <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a>.</p>
