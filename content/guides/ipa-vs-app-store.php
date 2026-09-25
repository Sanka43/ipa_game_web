<?php
$related = ['what-is-an-ipa-file', 'are-ipa-files-safe', 'sideload-games-on-iphone'];
$faq = [
    ['Is sideloading an IPA illegal?', 'Sideloading itself is not illegal, and Apple provides free developer signing for exactly this. Distributing or installing paid apps you do not own is copyright infringement.'],
    ['Are in-app purchases possible in sideloaded apps?', 'Sometimes, but they are unreliable. Purchases are tied to the original app\'s signature, so they may fail or be rejected. Buy in-app items only in App Store versions.'],
    ['Which is faster, a sideloaded app or the App Store version?', 'They perform the same. The code is identical. Only the signature differs.'],
];
?>
<p>A game from the App Store and the same game sideloaded as an IPA run the <em>same code</em>. What is different is who signed it, and that affects updates, saves, purchases and safety.</p>

<h2>At a glance</h2>
<div class="table-scroll"><table>
  <thead><tr><th></th><th>App Store install</th><th>Sideloaded IPA</th></tr></thead>
  <tbody>
    <tr><td>Signed by</td><td>Apple</td><td>You (Apple ID or developer account)</td></tr>
    <tr><td>Reviewed by Apple</td><td>Yes</td><td>No</td></tr>
    <tr><td>Expires</td><td>Never</td><td>7 days (free) / 1 year (paid)</td></tr>
    <tr><td>Automatic updates</td><td>Yes</td><td>No, you install new IPAs yourself</td></tr>
    <tr><td>iCloud saves &amp; Game Center</td><td>Yes</td><td>Often broken if the bundle ID changes</td></tr>
    <tr><td>Push notifications</td><td>Yes</td><td>Often missing</td></tr>
    <tr><td>In-app purchases</td><td>Yes, with refunds via Apple</td><td>Unreliable</td></tr>
    <tr><td>Family Sharing / Screen Time limits</td><td>Full support</td><td>Screen Time works, Family Sharing does not</td></tr>
    <tr><td>App limit</td><td>None</td><td>3 active (free Apple ID)</td></tr>
    <tr><td>Can install non-Store apps</td><td>No</td><td>Yes</td></tr>
  </tbody>
</table></div>

<h2>When the App Store is the better choice</h2>
<ul>
  <li>The game is available there. Most are, and they are free.</li>
  <li>You play online and want your account, purchases and cloud saves to work.</li>
  <li>You want automatic updates and zero maintenance.</li>
  <li>The device belongs to a child.</li>
</ul>
<p>That is why every game page on this site links to the official App Store listing wherever one exists.</p>

<h2>When an IPA is the better choice</h2>
<ul>
  <li><strong>Apps that are not in the App Store:</strong> open-source ports, homebrew and some emulators.</li>
  <li><strong>Developer betas and test builds</strong> that a developer has sent you.</li>
  <li><strong>Your own projects</strong> that you are testing on your phone.</li>
  <li><strong>An older version</strong> of an app you legitimately own, kept as a backup.</li>
</ul>

<h2>The safety difference</h2>
<p>Apple reviews App Store apps and can remotely disable malicious ones. A sideloaded IPA gets <strong>no review</strong>. iOS still sandboxes it, but whether it is safe depends on where it came from. Read <a href="<?= guide_url('are-ipa-files-safe') ?>">are IPA files safe?</a> before you sideload anything from an unfamiliar source.</p>
