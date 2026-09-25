<?php
$related = ['install-ipa-with-sidestore', 'how-to-install-ipa-on-iphone', 'ipa-installation-failed'];
$howto = [
    ['Install AltServer', 'Download AltServer from altstore.io and install it on your Windows PC or Mac.'],
    ['Connect your iPhone', 'Plug the iPhone in with a cable, unlock it and tap Trust This Computer.'],
    ['Install AltStore', 'From the AltServer menu, choose Install AltStore, pick your device and sign in with your Apple ID.'],
    ['Trust and enable Developer Mode', 'Trust your Apple ID in VPN & Device Management and turn on Developer Mode on iOS 16 or later.'],
    ['Sideload the IPA', 'Open AltStore, go to My Apps, tap + and choose the IPA file.'],
    ['Keep it refreshed', 'Leave AltServer running on the same Wi-Fi so AltStore can refresh your apps before the 7 days are up.'],
];
$faq = [
    ['Is AltStore free?', 'Yes. The classic AltStore, which sideloads any IPA, is free and works with a free Apple ID.'],
    ['Does AltStore need a jailbreak?', 'No. AltStore signs apps with your own Apple ID, the same way Xcode does for developers.'],
    ['What happens if I forget to refresh?', 'After 7 days the app will not open. Your data stays on the phone, so connect to AltServer, refresh, and the app works again with your progress intact.'],
    ['Is AltStore PAL the same thing?', 'No. AltStore PAL is the EU marketplace version. It installs apps that Apple has notarized from sources, not arbitrary IPA files.'],
];
?>
<p><strong>AltStore</strong> is the best-known way to sideload IPA files without jailbreaking. It uses your Apple ID to sign apps, the same way Apple's own Xcode signs apps for developers testing on their phones. A helper app on your computer, <strong>AltServer</strong>, installs AltStore and later refreshes your apps over Wi-Fi.</p>

<h2>What you need</h2>
<ul>
  <li>A Windows 10/11 PC or a Mac.</li>
  <li>An iPhone or iPad. AltStore supports a wide range of iOS versions; check the AltStore website for the current minimum.</li>
  <li>A cable for the first setup.</li>
  <li>An Apple ID. We strongly suggest a <strong>secondary Apple ID</strong> used only for sideloading.</li>
  <li><strong>Windows only:</strong> iTunes and iCloud. Follow AltStore's instructions on which versions to install, because the wrong ones are the most common cause of "device not found" errors.</li>
</ul>

<h2>Step 1: Install AltServer</h2>
<ol class="steps">
  <li>Go to the official AltStore website and download AltServer for your system.</li>
  <li><strong>Windows:</strong> run the installer. AltServer appears as a diamond icon in the system tray, and you may need to click the <em>^</em> arrow to see it.<br><strong>Mac:</strong> move AltServer to Applications and open it. The icon appears in the menu bar.</li>
  <li><strong>Windows:</strong> open iTunes, select your iPhone and tick <em>Sync with this iPhone over Wi-Fi</em>. This lets AltServer refresh apps without a cable later.</li>
</ol>

<h2>Step 2: Install AltStore on your iPhone</h2>
<ol class="steps">
  <li>Connect the iPhone with a cable, unlock it and tap <strong>Trust</strong>.</li>
  <li>Click the AltServer icon › <strong>Install AltStore</strong> › choose your iPhone.</li>
  <li>Enter your Apple ID and password. AltServer sends these only to Apple to create a signing certificate.</li>
  <li>After a minute, AltStore appears on your Home Screen.</li>
</ol>

<h2>Step 3: Trust AltStore and turn on Developer Mode</h2>
<ol class="steps">
  <li><em>Settings › General › VPN &amp; Device Management</em> › tap your Apple ID › <strong>Trust</strong>.</li>
  <li>iOS 16 and later: <em>Settings › Privacy &amp; Security › Developer Mode</em> › On › Restart › <strong>Turn On</strong>.</li>
  <li>Open AltStore and sign in with the same Apple ID (<em>Settings</em> tab).</li>
</ol>

<h2>Step 4: Sideload an IPA</h2>
<ol class="steps">
  <li>Save the <code>.ipa</code> to the Files app, either with a download in Safari or by AirDrop from your computer.</li>
  <li>Open AltStore › <strong>My Apps</strong> › tap <strong>+</strong> in the top corner.</li>
  <li>Pick the IPA. AltStore signs and installs it, and a progress ring shows on the app.</li>
  <li>Open the app from your Home Screen.</li>
</ol>
<?= note('tip', 'You can also open an IPA straight from Safari or Files with <b>Share › AltStore</b>.') ?>

<h2>Refreshing: the 7-day rule</h2>
<p>Apps signed with a free Apple ID stop opening after <strong>7 days</strong>. AltStore refreshes them for you when:</p>
<ul>
  <li>AltServer is running on your computer,</li>
  <li>the iPhone and computer are on the <strong>same Wi-Fi</strong>, and</li>
  <li>you open AltStore now and then (or tap <em>Refresh All</em>).</li>
</ul>
<p>The <em>My Apps</em> tab shows how many days each app has left. If an app has already expired, refresh it and it opens again. Your game saves are not lost.</p>

<h2>Free Apple ID limits</h2>
<div class="table-scroll"><table>
  <thead><tr><th>Limit</th><th>Free Apple ID</th><th>Paid developer ($99/yr)</th></tr></thead>
  <tbody>
    <tr><td>Active sideloaded apps</td><td>3 (AltStore counts as 1)</td><td>No practical limit</td></tr>
    <tr><td>New App IDs</td><td>10 per 7 days</td><td>Much higher</td></tr>
    <tr><td>Signature lifetime</td><td>7 days</td><td>1 year</td></tr>
  </tbody>
</table></div>
<p>With the 3-app limit, you can <em>deactivate</em> an app in AltStore to free a slot without losing its data, and reactivate it later.</p>

<h2>Common AltStore problems</h2>
<ul>
  <li><strong>"Could not find AltServer":</strong> the phone and computer are on different networks, or Wi-Fi sync is off (Windows), or a firewall is blocking AltServer.</li>
  <li><strong>"Device not found" on Windows:</strong> reinstall iTunes and iCloud using AltStore's recommended versions, then restart.</li>
  <li><strong>"Maximum number of apps":</strong> you hit the 3-app limit. Deactivate one app.</li>
  <li><strong>App closes immediately:</strong> it has expired or Developer Mode is off. See <a href="<?= guide_url('ipa-installation-failed') ?>">all fixes</a>.</li>
</ul>
<p>Want to drop the computer entirely? <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a> is built on AltStore and refreshes on the phone itself.</p>
