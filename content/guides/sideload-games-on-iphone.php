<?php
$related = ['how-to-install-ipa-on-iphone', 'ipa-vs-app-store', 'are-ipa-files-safe'];
$faq = [
    ['Will I lose my game progress when a sideloaded app expires?', 'No. Expiry only stops the app from opening. Your saves stay on the device until you delete the app. Refresh it and carry on.'],
    ['Do sideloaded games get updates?', 'Not automatically. You install the newer IPA over the old one. If the bundle ID matches, your data is kept.'],
    ['Does Game Center or iCloud save work in sideloaded games?', 'Often not. Signing tools may change the bundle ID, which disconnects the game from its iCloud container and Game Center. Use the game\'s own account login for cloud saves.'],
    ['Can I use a controller with sideloaded games?', 'Yes, if the game supports controllers. Pairing works the same way as for App Store games.'],
];
?>
<p>Sideloading means installing an app from outside the App Store. For games, it is mostly useful for homebrew titles, open-source ports, emulators, beta builds from developers and your own projects. This guide covers the game-specific details that general IPA guides skip.</p>

<h2>1. Choose your sideloading method</h2>
<p>If you only want one or two games, <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a> with a free Apple ID is enough. Remember the limit of 3 active apps, and the store app itself uses one slot. If you want a library of games, a paid developer account removes the limits and the weekly refreshes.</p>

<h2>2. Handle big game files</h2>
<ul>
  <li><strong>Download on Wi-Fi.</strong> Large IPAs over mobile data can fail or get cut short. Compare the checksum if one is provided.</li>
  <li><strong>Leave headroom.</strong> Signing unpacks the IPA, so you temporarily need about <strong>twice</strong> the file size free.</li>
  <li><strong>On-demand content.</strong> Many games download level packs after the first launch. The IPA size is only the starting point.</li>
</ul>

<h2>3. Save data and cloud progress</h2>
<p>Sideloaded games store data on your device like any other app. But signing tools often <strong>change the bundle ID</strong>, and that has side effects:</p>
<ul>
  <li><strong>iCloud saves</strong> may not sync, because the new ID has no access to the original iCloud container.</li>
  <li><strong>Game Center</strong> achievements and leaderboards may not load.</li>
  <li><strong>Push notifications</strong> often do not arrive.</li>
</ul>
<p>If the game has its own account system (email, Google, Apple sign-in), use it. That is the safest way to keep your progress.</p>

<h2>4. Updating a sideloaded game</h2>
<ol class="steps">
  <li>Get the new version's IPA.</li>
  <li>Install it through the same tool. It installs over the old copy.</li>
  <li>If the bundle ID matches, your saves are kept. If you get a "different app" error, back up first, because deleting the old one wipes its data.</li>
</ol>

<h2>5. Controllers and performance</h2>
<ul>
  <li>Pair Xbox, PlayStation, or MFi controllers in <em>Settings › Bluetooth</em>.</li>
  <li>Close background apps and turn off Low Power Mode for demanding games.</li>
  <li>Emulators that need <strong>JIT</strong> for full speed need an extra step on iOS. Follow the emulator's own documentation.</li>
</ul>

<h2>6. Stay safe</h2>
<p>Avoid "modded" or "hacked" game IPAs that promise unlimited coins. They are the most common way malware and account-stealing code get onto iPhones, and online games ban modified clients. Read <a href="<?= guide_url('are-ipa-files-safe') ?>">are IPA files safe?</a> and <a href="<?= guide_url('how-to-verify-ipa-file') ?>">verify every file</a>.</p>

<h2>Or skip sideloading entirely</h2>
<p>Most great iOS games are free on the App Store, with no refreshes and no limits. Browse <a href="<?= category_url('racing') ?>">racing</a>, <a href="<?= category_url('puzzle') ?>">puzzle</a> or <a href="<?= category_url('offline') ?>">offline</a> games and install them the normal way.</p>
