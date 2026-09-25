<?php
$related = ['install-ipa-with-altstore', 'install-ipa-with-sidestore', 'ipa-installation-failed'];
$howto = [
    ['Pick an install method', 'Choose AltStore, SideStore, Sideloadly, a paid developer account or TrollStore based on your iOS version and whether you have a computer.'],
    ['Get the IPA file', 'Download the IPA from a trusted source and save it to the Files app or your computer.'],
    ['Sign and install', 'Open the IPA in your signing tool and sign it with your Apple ID so it installs on your iPhone.'],
    ['Trust the developer', 'Go to Settings > General > VPN & Device Management and trust your Apple ID.'],
    ['Enable Developer Mode', 'On iOS 16 or later, turn on Settings > Privacy & Security > Developer Mode and restart.'],
    ['Open and refresh', 'Open the app. With a free Apple ID, refresh it every 7 days.'],
];
$faq = [
    ['Can I install an IPA file directly on iPhone?', 'Not by just tapping it. iOS only runs apps that are signed for your device, so an IPA has to be signed first, by Apple through the App Store, by your own Apple ID, or by a developer account.'],
    ['Do I need to jailbreak to install IPA files?', 'No. Every method in this guide works on a normal, non-jailbroken iPhone.'],
    ['Why do sideloaded apps stop working after 7 days?', 'Apple signs free Apple ID certificates for 7 days. Refresh the app before then (AltStore and SideStore can do this automatically), or use a paid developer account, which signs for a full year.'],
    ['How many IPA files can I install?', 'A free Apple ID allows 3 sideloaded apps at once, and AltStore or SideStore counts as one of them. A paid developer account has no practical limit.'],
];
?>
<p>An <strong>IPA file</strong> is an iOS app package. Unlike an Android APK, you cannot just tap it to install. iOS only runs code that has been <em>signed</em> for your device. Every method below does the same job in a different way: it signs the IPA so that your iPhone accepts it.</p>

<?= note('tip', '<b>Short version:</b> most people should use <a href="' . guide_url('install-ipa-with-altstore') . '">AltStore</a> if they have a computer nearby, or <a href="' . guide_url('install-ipa-with-sidestore') . '">SideStore</a> if they want to refresh apps from the phone itself. Both are free and use your Apple ID.') ?>

<h2>Before you start</h2>
<ul>
  <li><strong>An Apple ID.</strong> A free one works. We recommend a <em>separate</em> Apple ID just for sideloading, so your main account's password never goes into a third-party tool.</li>
  <li><strong>The IPA file</strong>, from a source you trust. If you're not sure, <a href="<?= guide_url('how-to-verify-ipa-file') ?>">verify the file</a> first.</li>
  <li><strong>Free storage.</strong> Allow about twice the IPA's size while it installs.</li>
  <li><strong>Your iOS version</strong> (<em>Settings › General › About</em>). It decides whether TrollStore is an option.</li>
</ul>

<h2>Every IPA install method compared</h2>
<div class="table-scroll"><table>
  <thead><tr><th>Method</th><th>Computer needed?</th><th>App lifetime</th><th>App limit</th><th>Cost</th></tr></thead>
  <tbody>
    <tr><td><a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a></td><td>Yes (setup + refresh)</td><td>7 days, auto-refresh</td><td>3 apps</td><td>Free</td></tr>
    <tr><td><a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a></td><td>Once, for setup</td><td>7 days, refresh on-device</td><td>3 apps</td><td>Free</td></tr>
    <tr><td>Sideloadly</td><td>Yes, every time</td><td>7 days</td><td>3 apps</td><td>Free</td></tr>
    <tr><td>Paid Apple Developer account</td><td>Depends on the signing tool</td><td>1 year</td><td>No practical limit</td><td>$99/year</td></tr>
    <tr><td>TrollStore</td><td>Usually once</td><td>Permanent</td><td>Unlimited</td><td>Free, only on some iOS versions</td></tr>
  </tbody>
</table></div>

<h2>Method 1: AltStore (best for most people)</h2>
<p>AltStore puts a small app store on your iPhone. A companion app, <strong>AltServer</strong>, runs on your Windows PC or Mac and re-signs your apps in the background whenever both devices are on the same Wi-Fi.</p>
<ol class="steps">
  <li>Install AltServer from the official AltStore website on your PC or Mac.</li>
  <li>Connect your iPhone with a cable, unlock it and tap <em>Trust This Computer</em>.</li>
  <li>From the AltServer menu, choose <em>Install AltStore</em>, pick your iPhone and sign in with your Apple ID.</li>
  <li>On the iPhone, open AltStore › <em>My Apps</em> › <strong>+</strong> and select the IPA from Files.</li>
</ol>
<p>The <a href="<?= guide_url('install-ipa-with-altstore') ?>">full AltStore guide</a> covers the Windows requirements and Wi-Fi refresh.</p>

<h2>Method 2: SideStore (refresh without a computer)</h2>
<p>SideStore is a community fork of AltStore. It refreshes apps <em>on the iPhone itself</em> using a local VPN connection, so after a one-time setup you never need the computer again. The trade-off is a slightly more technical setup. You create a <em>pairing file</em> once on a computer. <a href="<?= guide_url('install-ipa-with-sidestore') ?>">Follow the SideStore guide →</a></p>

<h2>Method 3: Sideloadly (simple one-off installs)</h2>
<p>Sideloadly is a desktop app for Windows and Mac. You drag in the IPA, type your Apple ID and click Start. It is the fastest way to install a single IPA, but it has no automatic refresh. After 7 days you connect the phone again and repeat.</p>

<h2>Method 4: Paid Apple Developer account</h2>
<p>An Apple Developer Program membership ($99/year) lifts the free-account limits. Apps stay signed for a year, and you can register up to 100 devices of each type. It is the most reliable option if you sideload a lot, or if you test your own games.</p>

<h2>Method 5: TrollStore (permanent, only on some iOS versions)</h2>
<p>TrollStore uses an iOS signing bug to install IPAs <em>permanently</em>, with no refresh and no app limit. It only works on specific iOS versions (roughly iOS 14.0 through 16.6.1, plus 16.7 RC and 17.0). Apple fixed the bug in later updates, so if you are on a newer version this option is not available. Do not downgrade or stay on old iOS versions just for it, because they miss security fixes.</p>

<h2>After installing: trust and Developer Mode</h2>
<ol class="steps">
  <li><strong>Trust the certificate:</strong> <em>Settings › General › VPN &amp; Device Management</em> › tap your Apple ID › <strong>Trust</strong>.</li>
  <li><strong>Turn on Developer Mode</strong> (iOS 16 and later): <em>Settings › Privacy &amp; Security › Developer Mode</em> › On. The iPhone restarts and asks you to confirm.</li>
  <li>Open the app while you are online. The first launch checks the certificate with Apple.</li>
</ol>
<?= note('warn', '<b>Seeing "Unable to Install" or an app that closes straight away?</b> Go through <a href="' . guide_url('ipa-installation-failed') . '">12 fixes for failed IPA installs</a>.') ?>

<h2>Which method should you choose?</h2>
<ul>
  <li><strong>Have a computer at home:</strong> AltStore.</li>
  <li><strong>Want to be free of the computer:</strong> SideStore.</li>
  <li><strong>Only need one app, once:</strong> Sideloadly.</li>
  <li><strong>On iOS 14.0–16.6.1 or 17.0:</strong> TrollStore.</li>
  <li><strong>Heavy sideloader or developer:</strong> a paid developer account.</li>
</ul>
<p>Most free games do not need any of this, because they install straight from the App Store. Browse <a href="<?= url('ipa-games/') ?>">IPA games</a>, and use sideloading only for apps that are not in the store.</p>
