<?php
$related = ['install-ipa-with-sidestore', 'install-ipa-with-altstore', 'ipa-installation-failed'];
$faq = [
    ['Why does my sideloaded app stop opening after a week?', 'Apps signed with a free Apple ID are valid for 7 days. When the signature expires, iOS refuses to open the app until it is signed again. Refreshing renews the signature without deleting the app or its data.'],
    ['Will I lose my save data if the app expires?', 'No. An expired app stays installed with its data. Refresh it and it opens again with your progress. Deleting the app is what removes the data.'],
    ['How early can I refresh?', 'You can refresh at any time. A good habit is to refresh once the app has 1 or 2 days left, or simply open your signing tool every few days.'],
    ['Does a paid Apple Developer account change this?', 'Yes. Apps signed with a paid developer account last 1 year instead of 7 days, so they need refreshing far less often.'],
];
?>
<p>Apps you sideload with a free Apple ID are only signed for <strong>7 days</strong>. After that iOS will not open them until they are signed again. That signing renewal is called a <em>refresh</em>, and it takes a few seconds if your setup is ready for it.</p>

<h2>What a refresh does</h2>
<p>A refresh re-signs the installed app with your Apple ID and extends the 7-day timer. The app is not reinstalled, so your saves and settings stay as they are. If the 7 days run out first, the app simply greys out or closes on launch. Refresh it and it works again.</p>

<h2>Refresh with AltStore</h2>
<ol class="steps">
  <li>Make sure AltServer is running on your Windows PC or Mac, and that the computer and the iPhone are on the same Wi-Fi network.</li>
  <li>Open AltStore on the iPhone and go to <strong>My Apps</strong>.</li>
  <li>Tap <strong>Refresh All</strong>, or tap <strong>Refresh</strong> next to a single app.</li>
  <li>Wait for the confirmation, then open the app to check that it launches.</li>
</ol>
<?= note('tip', '<b>Background refresh.</b> AltStore can also refresh by itself when AltServer is reachable, but iOS decides when background tasks run. Do not rely on it as the only plan.') ?>

<h2>Refresh with SideStore</h2>
<ol class="steps">
  <li>Turn on the VPN that SideStore uses (LocalDevVPN if that is how you set it up).</li>
  <li>Open SideStore and go to <strong>My Apps</strong>.</li>
  <li>Tap <strong>Refresh All</strong>.</li>
</ol>
<p>SideStore refreshes on the device, so no computer is needed once the pairing file is in place. See the <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore setup guide</a> for the one-time steps.</p>

<h2>If the app already expired</h2>
<ul>
  <li>Open your signing tool and refresh as above. The app should open again straight away.</li>
  <li>If the refresh fails, check the connection (same Wi-Fi for AltStore, VPN on for SideStore) and try again.</li>
  <li>If it still fails, the signing tool itself may have expired. Reinstall it, then see <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a>.</li>
</ul>

<h2>Tips to avoid surprises</h2>
<ul>
  <li>Refresh before travelling or before a long gap away from your computer.</li>
  <li>Remember the free limit of 3 active sideloaded apps. Remove apps you no longer use with <a href="<?= guide_url('remove-installed-ipa') ?>">this guide</a>.</li>
  <li>Third-party tools change over time, so menu names may differ slightly from the steps above.</li>
</ul>
