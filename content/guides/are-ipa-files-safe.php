<?php
$related = ['how-to-verify-ipa-file', 'install-ipa-without-computer', 'remove-installed-ipa'];
$faq = [
    ['Can an IPA file give my iPhone a virus?', 'An IPA cannot infect iOS itself or other apps, because the sandbox prevents it. It can still misbehave inside its own app: show fake login screens, track you, or send out anything you type into it.'],
    ['Is it safe to enter my Apple ID into AltStore or SideStore?', 'Both are open source and send your credentials only to Apple. Even so, using a separate Apple ID just for sideloading is the safest habit.'],
    ['Are "hacked" or "modded" game IPAs safe?', 'They are the riskiest kind of IPA. The code has been changed by an unknown person, often with extra libraries injected, and online games ban modified clients.'],
    ['What is the most dangerous thing a download site can ask for?', 'Installing an MDM (device management) profile, or one that adds a certificate or VPN. That can give someone else control over your device\'s settings, apps and network traffic. A Web Clip profile, which only adds a Home Screen icon, is harmless.'],
];
?>
<p>Short answer: <strong>an IPA is as safe as its source</strong>. iOS has strong protections that limit what any app can do, but sideloading skips Apple's review. So the real risks come from <em>where</em> the file came from and <em>how</em> you install it.</p>

<h2>What protects you: the iOS sandbox</h2>
<p>Every iOS app, sideloaded or not, runs in a <strong>sandbox</strong>. It cannot read other apps' data, change system files, or open your photos, contacts, location, camera or microphone without asking you first. That means an IPA <em>cannot</em> "infect" your iPhone the way a Windows virus can.</p>

<h2>The real risks</h2>
<h3>1. Modified ("modded") IPAs</h3>
<p>Someone takes a real game, injects extra code (usually a <code>.dylib</code> in the Frameworks folder), and redistributes it. That code runs with the game's permissions. It can capture anything you type into the game, including account logins, show phishing screens, serve ads or track you.</p>

<h3>2. Malicious profiles</h3>
<p>Some "no computer" install sites ask you to install an <strong>MDM profile</strong>, or a configuration profile that adds <strong>certificates</strong> or a <strong>VPN</strong>. That is far more dangerous than any single app. It can install and remove apps, change settings, add root certificates and route your web traffic.</p>
<p>Not every profile is risky. The iOS install screen lists what a profile contains. A <strong>Web Clip</strong> only adds a Home Screen icon for a website, and you can remove it at any time.</p>

<h3>3. Apple ID theft</h3>
<p>Unknown signing tools and websites that ask for your Apple ID password could keep it. Use tools you trust, turn on two-factor authentication, and ideally use a <strong>separate Apple ID</strong> just for sideloading.</p>

<h3>4. Scam download pages</h3>
<p>"Complete a survey to unlock your download", "human verification required", countdowns that never finish and fake "virus detected" pop-ups are all signs of a scam. A real IPA download never asks for any of these.</p>

<h3>5. Account bans</h3>
<p>Online games detect modified clients. Even if the IPA is not malicious, using it can get your game account banned for good.</p>

<h2>Safety checklist</h2>
<ul class="checks">
  <li class="ok">Prefer the <strong>App Store</strong> whenever the game is there.</li>
  <li class="ok">For other apps, download from the <strong>developer's own site or GitHub releases</strong>.</li>
  <li class="ok"><a href="<?= guide_url('how-to-verify-ipa-file') ?>">Verify the checksum and signature</a> when the source publishes one.</li>
  <li class="ok">Use open-source signers: <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a>.</li>
  <li class="ok">Use a <strong>secondary Apple ID</strong> for signing.</li>
  <li class="warn">Never install MDM, certificate or VPN profiles from download sites. Check what a profile contains before you tap Install.</li>
  <li class="warn">Avoid "hacked", "unlimited coins" or "premium unlocked" IPAs.</li>
  <li class="warn">Never type your main passwords into a sideloaded app you do not fully trust.</li>
</ul>

<h2>Already installed something suspicious?</h2>
<ol class="steps">
  <li>Delete the app, then check <em>Settings › General › VPN &amp; Device Management</em> for unknown profiles and remove them.</li>
  <li>Change the password of any account you logged into inside that app.</li>
  <li>Change your Apple ID password if you typed it into an unknown tool.</li>
</ol>
<p>Full steps: <a href="<?= guide_url('remove-installed-ipa') ?>">how to remove an installed IPA</a>.</p>
