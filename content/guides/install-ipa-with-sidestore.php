<?php
$related = ['install-ipa-without-computer', 'install-ipa-with-altstore', 'ipa-installation-failed'];
$howto = [
    ['Prepare a computer once', 'On a Windows, Mac or Linux computer, download the installer recommended on the official SideStore website.'],
    ['Install SideStore and create the pairing file', 'Connect your iPhone, trust the computer and let the installer sideload SideStore and generate the pairing file.'],
    ['Install the local VPN app', 'Install the VPN app named in the SideStore docs from the App Store and turn its tunnel on.'],
    ['Trust and enable Developer Mode', 'Trust your Apple ID in VPN & Device Management and turn on Developer Mode.'],
    ['Sign in and sideload', 'Open SideStore, sign in with your Apple ID, then go to My Apps, tap + and choose an IPA.'],
    ['Refresh on the phone', 'Every few days, turn on the VPN and tap Refresh All in SideStore. No computer is needed.'],
];
$faq = [
    ['Does SideStore need a computer?', 'Only once, to install it and create the pairing file. After that you install and refresh apps on the iPhone alone.'],
    ['Why does SideStore need a VPN?', 'The VPN is local. It runs on the phone and sends nothing to outside servers. It makes iOS think a computer is connected, so SideStore can sign apps on the device.'],
    ['Is SideStore the same as AltStore?', 'SideStore is an open-source fork of AltStore. The app works the same way, but refreshing happens on the device, so you do not need AltServer.'],
    ['What breaks the pairing file?', 'Resetting "Trust This Computer" (Settings > General > Transfer or Reset > Reset Location & Privacy), erasing the phone, and sometimes a major iOS update. Create a new pairing file on a computer to fix it.'],
];
?>
<p><strong>SideStore</strong> is an open-source fork of AltStore with one big improvement: it signs and refreshes apps <strong>on the iPhone itself</strong>. After a one-time setup on a computer, you can install IPAs and keep them alive anywhere, with no AltServer and no cable.</p>

<?= note('info', 'SideStore\'s setup tools change fairly often. The steps below describe how it works. Always download the installer and the VPN app that the <b>official SideStore website</b> recommends right now.') ?>

<h2>How SideStore works</h2>
<p>Signing an app normally needs a computer to talk to the iPhone over a "lockdown" connection. SideStore uses a <strong>pairing file</strong>, the same trust record your iPhone creates when you tap <em>Trust This Computer</em>. It combines that with a <strong>local VPN</strong> that loops traffic back to the device. iOS then believes a trusted computer is connected, so SideStore can install and refresh apps by itself.</p>

<h2>What you need</h2>
<ul>
  <li>An iPhone or iPad on a supported iOS version (see the SideStore site).</li>
  <li>A computer (Windows, Mac or Linux) for <strong>one-time</strong> setup.</li>
  <li>An Apple ID. A secondary account is recommended.</li>
  <li>A cable.</li>
</ul>

<h2>Step 1: Install SideStore and create the pairing file</h2>
<ol class="steps">
  <li>On your computer, download the installer tool linked from the official SideStore website.</li>
  <li>Connect the iPhone, unlock it and tap <strong>Trust</strong>.</li>
  <li>Run the installer, sign in with your Apple ID and choose your device. It sideloads SideStore and places the pairing file on the phone.</li>
  <li>When it finishes, unplug. From now on the computer is optional.</li>
</ol>

<h2>Step 2: Set up the local VPN</h2>
<ol class="steps">
  <li>Install the VPN app that the SideStore docs name. It comes from the App Store.</li>
  <li>Open it and allow it to add a VPN configuration.</li>
  <li>Turn the tunnel <strong>on</strong> whenever you install or refresh apps. You can leave it on all the time, because it does not route your internet traffic anywhere.</li>
</ol>

<h2>Step 3: Trust, Developer Mode, sign in</h2>
<ol class="steps">
  <li><em>Settings › General › VPN &amp; Device Management</em> › your Apple ID › <strong>Trust</strong>.</li>
  <li><em>Settings › Privacy &amp; Security › Developer Mode</em> › On, then restart.</li>
  <li>Open SideStore › <em>Settings</em> › sign in with your Apple ID. If it asks for the pairing file, select it from Files.</li>
</ol>

<h2>Step 4: Install an IPA</h2>
<ol class="steps">
  <li>With the VPN on, open SideStore › <strong>My Apps</strong> › <strong>+</strong>.</li>
  <li>Choose the <code>.ipa</code> from Files. SideStore signs and installs it.</li>
</ol>

<h2>Refreshing apps</h2>
<p>Free Apple ID apps still expire after <strong>7 days</strong>, and the 3-app limit still applies. The difference is that you refresh on the phone:</p>
<ol class="steps">
  <li>Turn the VPN tunnel on.</li>
  <li>Open SideStore › <em>My Apps</em> › <strong>Refresh All</strong>.</li>
</ol>
<p>You can add a Shortcuts automation, for example "every 5 days, open SideStore", so you never forget. SideStore itself needs refreshing too, so keep an eye on its own countdown.</p>

<h2>Troubleshooting SideStore</h2>
<ul>
  <li><strong>"Could not connect" / timeouts:</strong> the VPN tunnel is off, or another VPN is active. Turn off other VPNs and try again.</li>
  <li><strong>"Invalid pairing file":</strong> the trust record was reset. Create a new pairing file on a computer.</li>
  <li><strong>SideStore itself expired:</strong> you have to reinstall it from a computer, so refresh SideStore before it runs out.</li>
  <li>Other errors: see <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a>.</li>
</ul>
