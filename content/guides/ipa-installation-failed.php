<?php
$related = ['how-to-install-ipa-on-iphone', 'install-ipa-with-altstore', 'install-ipa-with-sidestore'];
$faq = [
    ['Why does my sideloaded app open and close immediately?', 'Usually the signature has expired (after 7 days on a free Apple ID), Developer Mode is off, or the certificate is not trusted yet. Refresh the app and check both settings.'],
    ['What does "integrity could not be verified" mean?', 'iOS cannot validate the app\'s signature for your device. The IPA was not signed for this iPhone, the signature is broken, or the certificate was revoked. Re-sign it with your own tool.'],
    ['Why does it say the maximum number of apps has been reached?', 'A free Apple ID allows 3 active sideloaded apps per device, including AltStore or SideStore. Deactivate or delete one.'],
];
?>
<p>IPA installs usually fail for one of a dozen reasons. Find your error below and apply the fix. Most are solved in under a minute.</p>

<h2>1. "Unable to Install" / "Integrity could not be verified"</h2>
<p><strong>Cause:</strong> the IPA isn't signed for your device, the signature is broken, or the certificate was revoked. You see this most often with IPAs from "tap to install" websites.<br>
<strong>Fix:</strong> sign it yourself with <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a>. If it still fails, the file may be damaged, so <a href="<?= guide_url('how-to-verify-ipa-file') ?>">check its checksum</a>.</p>

<h2>2. App opens then closes instantly</h2>
<p><strong>Cause:</strong> the 7-day signature expired, Developer Mode is off, or the developer is not trusted.<br>
<strong>Fix:</strong> refresh in AltStore/SideStore → turn on <em>Settings › Privacy &amp; Security › Developer Mode</em> → trust your Apple ID in <em>Settings › General › VPN &amp; Device Management</em>.</p>

<h2>3. "Untrusted Developer"</h2>
<p><strong>Fix:</strong> <em>Settings › General › VPN &amp; Device Management</em> › tap your Apple ID › <strong>Trust</strong>.</p>

<h2>4. "Unable to Verify App – An internet connection is required"</h2>
<p><strong>Cause:</strong> the first launch of a developer-signed app checks the certificate with Apple.<br>
<strong>Fix:</strong> connect to the internet and open the app again. Turn off any DNS-blocking or ad-blocking profile that blocks Apple's servers.</p>

<h2>5. "Maximum number of apps for free development profiles"</h2>
<p><strong>Cause:</strong> the free Apple ID limit is 3 active apps, and the store app counts.<br>
<strong>Fix:</strong> in AltStore/SideStore, <em>deactivate</em> an app you are not using (its data stays), or use a paid developer account.</p>

<h2>6. "Cannot register more App IDs" / 10 App ID limit</h2>
<p><strong>Cause:</strong> free Apple IDs can create only 10 App IDs per 7 days.<br>
<strong>Fix:</strong> wait for the oldest one to expire (AltStore shows the dates), or use a second Apple ID.</p>

<h2>7. "This app requires iOS X or later"</h2>
<p><strong>Cause:</strong> the IPA's <code>MinimumOSVersion</code> is higher than your iOS.<br>
<strong>Fix:</strong> update iOS, or find an older version of the app built for your iOS. Our game pages list the minimum iOS for each version.</p>

<h2>8. Conflicts with the App Store version</h2>
<p><strong>Cause:</strong> the same bundle ID is already installed from the App Store.<br>
<strong>Fix:</strong> delete the App Store copy first. Back up any progress, because deleting wipes local data.</p>

<h2>9. Not enough storage</h2>
<p><strong>Fix:</strong> signing temporarily needs about <strong>twice</strong> the IPA's size. Free up space in <em>Settings › General › iPhone Storage</em>.</p>

<h2>10. AltServer can't find the iPhone</h2>
<p><strong>Fix:</strong> put both devices on the same Wi-Fi. On Windows, enable <em>Sync with this iPhone over Wi-Fi</em> in iTunes and use the iTunes/iCloud versions AltStore recommends. Allow AltServer through the firewall, or plug in the cable.</p>

<h2>11. SideStore can't connect</h2>
<p><strong>Fix:</strong> turn on the local VPN tunnel and disable any other VPN. If you see "invalid pairing file", create a new pairing file on a computer.</p>

<h2>12. Encrypted or corrupted IPA</h2>
<p><strong>Cause:</strong> IPAs copied from the App Store are FairPlay-encrypted and tied to the account that downloaded them, so they won't run after re-signing. A cut-off download gives the same symptoms.<br>
<strong>Fix:</strong> download the IPA again from the developer's official source and <a href="<?= guide_url('how-to-verify-ipa-file') ?>">verify it</a>.</p>

<h2>Still stuck?</h2>
<ul>
  <li>Restart the iPhone. It clears many signing and installd glitches.</li>
  <li>Update AltStore/SideStore to their latest versions.</li>
  <li>Sign out of the tool and sign back in to get a fresh certificate.</li>
  <li>Check that the date and time are set automatically (<em>Settings › General › Date &amp; Time</em>).</li>
</ul>
