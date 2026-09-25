<?php
$related = ['ipa-vs-app-store', 'how-to-install-ipa-on-iphone', 'how-to-verify-ipa-file'];
$faq = [
    ['What does IPA stand for?', 'IPA stands for iOS App Store Package. It is the file format for apps and games on iPhone and iPad.'],
    ['Can I open an IPA file on Windows?', 'You can look inside it. Rename it to .zip and extract it. You cannot run it on Windows, because it contains ARM code built for iOS.'],
    ['Is an IPA the same as an APK?', 'They do the same job for different systems. APK is Android\'s package, IPA is iOS\'s. An IPA will not install on Android, and an APK will not install on iPhone.'],
    ['Can I run IPA files on a Mac?', 'Macs with Apple silicon (M1 and later) can run many iPhone and iPad apps, but only when they are properly signed, usually through the Mac App Store.'],
];
?>
<p>An <strong>IPA file</strong> (<em>iOS App Store Package</em>) is the file format for apps and games on iPhone and iPad. Every app you have ever installed from the App Store arrived as an IPA, even though you never saw the file.</p>

<h2>What's inside an IPA</h2>
<p>An IPA is just a <strong>ZIP archive</strong> with a fixed layout. Rename <code>game.ipa</code> to <code>game.zip</code>, extract it, and you will find:</p>
<pre><code>Payload/
  GameName.app/
    GameName               ← the compiled program (ARM64 machine code)
    Info.plist             ← name, bundle ID, version, minimum iOS
    Assets.car             ← icons and images
    Frameworks/            ← bundled libraries (.framework / .dylib)
    _CodeSignature/        ← the signature that iOS checks
    embedded.mobileprovision  ← which devices/accounts may run it (sideloaded builds)
    …game data, sounds, levels
</code></pre>
<p>The <strong>Info.plist</strong> holds the most useful details:</p>
<ul>
  <li><code>CFBundleIdentifier</code>: the bundle ID, for example <code>com.developer.game</code>. It is the app's unique name.</li>
  <li><code>CFBundleShortVersionString</code>: the version you see, for example 1.4.2.</li>
  <li><code>MinimumOSVersion</code>: the oldest iOS it runs on.</li>
</ul>

<h2>Why IPAs must be signed</h2>
<p>iOS refuses to run any code that is not <strong>code-signed</strong> by a certificate it trusts. That is the core of iPhone security. There are three kinds of signature:</p>
<ol>
  <li><strong>App Store:</strong> Apple re-signs every app after review. It runs on any device.</li>
  <li><strong>Developer:</strong> signed with an Apple ID (free, 7 days) or a developer account (1 year). It runs only on devices listed in its provisioning profile. This is what <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> and <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a> use.</li>
  <li><strong>Enterprise:</strong> meant for a company's internal apps. It is often misused by "no computer" installers, and Apple revokes it regularly.</li>
</ol>
<p>This is why you cannot just tap an IPA to install it the way you install an APK on Android. It has to be signed for your device first.</p>

<h2>App Store IPAs are encrypted</h2>
<p>IPAs delivered by the App Store are protected with Apple's <strong>FairPlay</strong> encryption, which is tied to the Apple ID that downloaded them. That is why an App Store copy cannot simply be copied to another person's phone. Sideloading is meant for apps you have the right to install: open-source projects, homebrew, betas and your own builds.</p>

<h2>IPA vs APK</h2>
<div class="table-scroll"><table>
  <thead><tr><th></th><th>IPA (iOS)</th><th>APK (Android)</th></tr></thead>
  <tbody>
    <tr><td>Format</td><td>ZIP archive</td><td>ZIP archive</td></tr>
    <tr><td>Install from a file?</td><td>Only once it is signed for your device</td><td>Yes, after allowing unknown sources</td></tr>
    <tr><td>Signature expiry</td><td>7 days (free) / 1 year (paid)</td><td>No expiry</td></tr>
    <tr><td>Runs on the other OS?</td><td>No</td><td>No</td></tr>
  </tbody>
</table></div>

<h2>Where to get IPA files safely</h2>
<ul>
  <li>The <strong>App Store</strong>, for any regular game. Our game pages link to it directly.</li>
  <li>The developer's own website or GitHub releases, for open-source apps and emulators.</li>
  <li>Developer beta programs such as TestFlight.</li>
</ul>
<p>Before you install an IPA from anywhere else, read <a href="<?= guide_url('are-ipa-files-safe') ?>">are IPA files safe?</a> and <a href="<?= guide_url('how-to-verify-ipa-file') ?>">how to verify an IPA</a>.</p>
