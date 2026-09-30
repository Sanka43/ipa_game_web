<?php
$related = ['install-ipa-with-sidestore', 'install-ipa-without-computer', 'ipa-installation-failed'];
$howto = [
    ['Prepare your devices', 'Install iTunes (Windows only) and iloader on the computer, and LocalDevVPN on the iPhone or iPad.'],
    ['Install SideStore with iloader', 'Sign in with your Apple ID in iloader, connect the device, choose it and pick SideStore (Stable).'],
    ['Trust and enable Developer Mode', 'Trust your Apple ID in VPN & Device Management and turn on Developer Mode (iOS 16 or later).'],
    ['Connect LocalDevVPN and refresh SideStore', 'Connect the VPN, open SideStore and refresh SideStore from My Apps.'],
    ['Install LiveContainer + SideStore', 'Download the LiveContainer+SideStore IPA, add it in SideStore and choose Keep App Extensions (Use Main Profile).'],
    ['Link the pairing file and clean up', 'Select the pairing file inside LiveContainer, refresh it, delete the standalone SideStore, then import the certificate and run the JIT-Less test.'],
];
$faq = [
    ['What is LiveContainer?', 'An app that runs other iOS apps inside itself, without installing each one separately. Apps run inside LiveContainer do not use up your free-account app slots.'],
    ['Why use LiveContainer with SideStore?', 'A free Apple ID allows only 3 sideloaded apps. LiveContainer + SideStore takes one slot and lets you run as many apps as you want inside it.'],
    ['Do I still need to refresh every 7 days?', 'Yes. LiveContainer and any app installed through SideStore expire after 7 days on a free Apple ID. Turn on LocalDevVPN and refresh in SideStore, or automate it with Shortcuts.'],
    ['Does it need a computer?', 'Only for the first setup with iloader. After that, everything happens on the device.'],
    ['Does it work on iOS 26.4?', 'Not yet with this method. iOS 26.4 changed how refreshing works, so wait for an updated guide if you are on 26.4. The steps here cover iOS 15.0 to 26.3.'],
    ['Stable or Nightly?', 'Stable is the recommended release. Nightly has newer features, such as sources inside LiveContainer, but can be buggier.'],
];
?>
<p><strong>LiveContainer + SideStore</strong> is the most flexible way to sideload on iPhone and iPad without paying for a developer account. SideStore installs and refreshes apps on the device. LiveContainer runs other apps <em>inside itself</em>, so they do not count toward the free 3-app limit. Set it up once and keep as many games as your storage allows.</p>

<?= note('info', 'This guide supports <b>iOS 15.0 – 26.3</b>. iOS 26.4 broke the refresh method used here, so wait for an updated guide if you are on 26.4. Setup tools change often, so check the official <a href="https://docs.sidestore.io" rel="noopener" target="_blank">SideStore docs</a> and <a href="https://livecontainer.github.io/docs/intro" rel="noopener" target="_blank">LiveContainer docs</a> if anything looks different.') ?>
<?= note('warn', 'This guide assumes SideStore and LiveContainer are <b>not</b> installed yet. If you already have them, delete both first to avoid conflicts.') ?>

<h2>What are SideStore and LiveContainer?</h2>
<p>SideStore installs <code>.ipa</code> files on your device and refreshes them using a local VPN. LiveContainer runs iOS apps without installing them one by one. Because a free Apple ID allows only 3 installed apps, a special LiveContainer build that includes SideStore is used: one slot for LiveContainer, one spare slot, and unlimited apps inside LiveContainer.</p>
<p>The method uses community-made tools and Apple-approved sideloading, and needs a computer only for the initial setup. For plain SideStore, see the <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore guide</a>.</p>

<h2>What you need</h2>
<ul>
  <li><strong>Computer:</strong> iTunes (Windows only) and <a href="https://github.com/nab138/iloader/releases" rel="noopener" target="_blank">iloader</a>.</li>
  <li><strong>iPhone / iPad:</strong> <a href="https://apps.apple.com/us/app/localdevvpn/id6755608044" rel="noopener" target="_blank">LocalDevVPN</a> from the App Store.</li>
  <li>An Apple ID (a separate one for sideloading is recommended) and a USB cable.</li>
</ul>

<h2>Step 1: Install SideStore with iloader</h2>
<ol class="steps">
  <li>Open iloader, choose <strong>Add Account +</strong> and sign in with your Apple ID.</li>
  <li>Plug in your iPhone or iPad. Press <strong>Refresh Devices</strong> if it does not show up.</li>
  <li>Under <em>Devices</em>, select your device.</li>
  <li>Under <em>Installers</em>, choose <strong>SideStore (Stable)</strong> and wait for the install to finish.</li>
</ol>

<h2>Step 2: Set up SideStore</h2>
<ol class="steps">
  <li>Go to <em>Settings › General › VPN &amp; Device Management</em>, tap your Apple ID email and <strong>Trust</strong> it.</li>
  <li>Go to <em>Settings › Privacy &amp; Security</em>, scroll to <em>Security</em> and turn on <strong>Developer Mode</strong> (iOS 16+). Follow the prompts and let the device restart.</li>
  <li>Open <strong>LocalDevVPN</strong> and tap <strong>Connect</strong>. Allow the VPN configuration and enter your passcode.</li>
  <li>Open <strong>SideStore › My Apps</strong> and tap <strong>7 DAYS</strong> next to SideStore.</li>
  <li>Sign in with the same Apple ID you used in iloader.</li>
</ol>
<?= note('info', 'LocalDevVPN is required for SideStore to install and refresh apps. Always connect it first.') ?>
<p>SideStore now works as a normal sideloading app and also supports AltStore sources. If you only want SideStore you can stop here, but you will have just 2 free slots. Continue to get LiveContainer.</p>

<h2>Step 3: Switch to LiveContainer + SideStore</h2>
<ol class="steps">
  <li>On the device, download the latest <strong>LiveContainer + SideStore</strong> IPA from the <a href="https://www.livecontainer.site/download/" rel="noopener" target="_blank">LiveContainer download</a> page. Stable is recommended; Nightly is for testers.</li>
  <li>In SideStore, open <em>My Apps</em>, tap <strong>+</strong> (top-left) and select the downloaded <code>LiveContainer+SideStore.ipa</code>.</li>
  <li>When prompted, choose <strong>Keep App Extensions (Use Main Profile)</strong>. Wait for the install, then open LiveContainer.</li>
  <li>Tap the <strong>SideStore icon</strong> (top-left) in LiveContainer. When asked for the pairing file, go to <em>On My iPhone/iPad › SideStore</em> and pick <code>ALTPairingFile.mobiledevicepairing</code>.</li>
  <li>Open <em>My Apps</em> and tap <strong>7 DAYS</strong> next to LiveContainer. Sign in with the same Apple ID and wait for the refresh.</li>
  <li><strong>Delete the standalone SideStore app.</strong></li>
  <li>Close and reopen LiveContainer. Go to <em>Settings › Import Certificate From SideStore</em> and confirm.</li>
  <li>Tap <em>JIT-Less Mode Diagnose › Test JIT-Less Mode</em>. The test should pass.</li>
</ol>

<h2>How to use it</h2>
<ul>
  <li>Apps installed <strong>inside LiveContainer</strong> do <strong>not</strong> count toward the 3-app limit.</li>
  <li>Apps installed <strong>with SideStore</strong>, even from inside LiveContainer, <strong>do</strong> count toward the limit.</li>
  <li>Turn on LocalDevVPN before installing or refreshing.</li>
  <li>LiveContainer (Nightly) can also load AltStore-style sources inside the app.</li>
  <li>Refresh every <strong>7 days</strong>. You can automate this with the Shortcuts app and Automations.</li>
</ul>

<h2>Troubleshooting</h2>
<ul>
  <li><strong>Can't install or refresh:</strong> LocalDevVPN is off or another VPN is active. Turn it on and disable other VPNs.</li>
  <li><strong>Pairing file not found:</strong> open the Files app › <em>On My iPhone › SideStore</em>. If it is missing, redo Step 1 with iloader.</li>
  <li><strong>JIT-Less test fails:</strong> run <em>Import Certificate From SideStore</em> again, then re-test.</li>
  <li><strong>On iOS 26.4:</strong> this method does not work yet. Wait for an updated guide.</li>
  <li><strong>Support:</strong> LiveContainer + SideStore bundles an older SideStore nightly. Ask in the LiveContainer GitHub issues or the #support-forum on the SideStore Discord, <em>not</em> the normal SideStore channels.</li>
  <li>Other errors: see <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a>.</li>
</ul>

<h2>Credits and links</h2>
<p>These tools are built by volunteers, so please support them. Based on the guide by eli (sinceohsix), proof-read by suprstarrd: <a href="https://gist.github.com/sinceohsix/688637ac04695d1ff38f844acc8ba7f3" rel="noopener" target="_blank">original guide</a>.</p>
<ul>
  <li><a href="https://docs.sidestore.io" rel="noopener" target="_blank">SideStore docs</a> · <a href="https://github.com/SideStore/SideStore" rel="noopener" target="_blank">GitHub</a> · <a href="https://sidestore.io" rel="noopener" target="_blank">website</a></li>
  <li><a href="https://livecontainer.github.io/docs/intro" rel="noopener" target="_blank">LiveContainer docs</a> · <a href="https://github.com/LiveContainer/LiveContainer" rel="noopener" target="_blank">GitHub</a></li>
  <li><a href="https://github.com/nab138/iloader" rel="noopener" target="_blank">iloader</a></li>
</ul>
