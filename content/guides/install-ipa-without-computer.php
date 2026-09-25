<?php
$related = ['install-ipa-with-sidestore', 'are-ipa-files-safe', 'how-to-install-ipa-on-iphone'];
$faq = [
    ['Can I install an IPA with no computer at all?', 'For most people, not the very first time. SideStore needs a computer once for setup and then works on its own. Services that claim to need no computer usually rely on enterprise certificates that Apple revokes, or on paid device registration.'],
    ['Are "no computer" IPA websites safe?', 'Many are not. Before you install any profile, read what the iOS install screen says it contains. A Web Clip only adds a Home Screen icon. Refuse anything with device management (MDM), certificates or VPN settings, because those can give a stranger control over your iPhone.'],
    ['Why did my app stop opening after a few days?', 'If you used a web installer that relies on an enterprise certificate, Apple probably revoked the certificate. It will keep happening. Switch to SideStore or a developer account.'],
];
?>
<p>"Install IPA without a computer" is one of the most searched sideloading questions, and one of the most misleading. Here is what actually works, what needs a computer <em>once</em>, and which "no PC" services to avoid.</p>

<h2>The honest answer</h2>
<p>iOS will only run an app if it is signed for your device. Signing with <em>your own</em> Apple ID needs a trusted connection to the phone, and that trust has to be created by a computer at least once. So the practical options are:</p>
<ol>
  <li><strong>Computer once, then never again:</strong> SideStore.</li>
  <li><strong>No computer, but paid:</strong> signing with a developer account, either yours or a service's.</li>
  <li><strong>No computer, but risky:</strong> web installers that use enterprise certificates.</li>
  <li><strong>No computer, limited:</strong> TrollStore on a small set of old iOS versions.</li>
</ol>

<h2>Option 1: SideStore (recommended)</h2>
<p>You borrow a computer for about 15 minutes to install SideStore and create a pairing file. From then on, you install, update and refresh IPAs entirely on the iPhone, with the help of a local VPN app. It is free and it uses your own Apple ID, so no third party ever holds your signing certificate.</p>
<p><a class="btn btn-primary" href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore setup guide →</a></p>

<h2>Option 2: A paid developer account</h2>
<p>With an Apple Developer Program membership ($99/year) you get a certificate that lasts a year. Some on-device signing apps can use your developer certificate to install IPAs with no computer involved. Some services also sell "UDID registration", which adds your device to <em>their</em> developer account. That works, but you are trusting a stranger's account. If Apple bans it, your apps stop working.</p>

<h2>Option 3: Enterprise-certificate web installers (avoid)</h2>
<p>Websites that let you "tap to install" signed IPAs straight from Safari usually use an <strong>enterprise certificate</strong>. Apple issues these to companies for internal apps only. Using them for public distribution breaks Apple's rules, so Apple revokes them regularly, and every app signed with them stops opening at once.</p>
<?= note('warn', '<b>Red flag:</b> if a profile contains <b>device management (MDM)</b>, a <b>certificate</b> or <b>VPN</b> settings, stop. An MDM profile can install apps, change settings and route your traffic. A profile that is only a <b>Web Clip</b> just adds a Home Screen icon. Remove unknown profiles in <em>Settings › General › VPN &amp; Device Management</em>.') ?>

<h2>Option 4: TrollStore (old iOS only)</h2>
<p>On iOS versions affected by a specific signing bug (roughly 14.0–16.6.1, 16.7 RC and 17.0), TrollStore installs IPAs permanently with no refresh. Installing TrollStore itself usually needs another tool first, and it does not work at all on current iOS versions.</p>

<h2>What about the EU?</h2>
<p>In the European Union, iOS 17.4 and later support <strong>alternative app marketplaces</strong> such as AltStore PAL. They install directly on the phone with no computer. But apps from them must be notarized by Apple and come from the marketplace's sources. You cannot drop in any IPA file you like.</p>

<h2>Our recommendation</h2>
<ul>
  <li>Most people: set up <strong>SideStore</strong> once and forget the computer.</li>
  <li>Sideload a lot: get a <strong>developer account</strong>.</li>
  <li>Never: install MDM, certificate or VPN profiles from download sites, or type your main Apple ID password into an unknown signing service.</li>
</ul>
<p>And remember that many popular games need no sideloading at all. Browse <a href="<?= category_url('latest') ?>">the latest IPA games</a>, which install straight from the App Store.</p>
