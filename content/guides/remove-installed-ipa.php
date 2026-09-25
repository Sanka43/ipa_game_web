<?php
$related = ['are-ipa-files-safe', 'ipa-installation-failed', 'how-to-install-ipa-on-iphone'];
$howto = [
    ['Delete the app', 'Long-press the app icon, tap Remove App, then Delete App.'],
    ['Remove it from your signing tool', 'Open AltStore or SideStore, go to My Apps and remove the app so it is no longer refreshed.'],
    ['Check profiles', 'Open Settings > General > VPN & Device Management and remove any profiles you don\'t recognise.'],
    ['Turn off Developer Mode', 'If you no longer sideload, turn off Settings > Privacy & Security > Developer Mode.'],
];
$faq = [
    ['Does deleting a sideloaded app delete my saves?', 'Yes, local data is removed with the app. Progress that syncs to the game\'s own account or server is kept.'],
    ['Why is my Apple ID still listed under VPN & Device Management?', 'It stays while at least one app signed with it is installed. Remove the last one and the entry disappears.'],
    ['Should I turn off Developer Mode?', 'If you have stopped sideloading, yes. It removes one extra way for untrusted code to run. You can turn it back on at any time.'],
];
?>
<p>Removing a sideloaded game is just like deleting any other app. If you want a completely clean device, though, a few leftovers are worth checking: the signing tool, profiles, and Developer Mode.</p>

<h2>Step 1: Delete the app</h2>
<p><strong>From the Home Screen:</strong> long-press the icon › <strong>Remove App</strong> › <strong>Delete App</strong> › Delete.</p>
<p><strong>From Settings:</strong> <em>Settings › General › iPhone Storage</em> › pick the app › <strong>Delete App</strong>.</p>
<?= note('tip', '<b>Want to keep the save data?</b> Choose <b>Offload App</b> instead. It removes the app but keeps its documents and data, so reinstalling the same bundle ID brings your progress back.') ?>

<h2>Step 2: Remove it from AltStore or SideStore</h2>
<p>Open the tool › <strong>My Apps</strong>. If the app is still listed there, remove it so it stops using one of your 3 active slots and isn't refreshed any more.</p>

<h2>Step 3: Check profiles and certificates</h2>
<p>Go to <em>Settings › General › VPN &amp; Device Management</em>:</p>
<ul>
  <li><strong>Developer App</strong> (your Apple ID): disappears on its own once no apps signed with it remain.</li>
  <li><strong>Configuration Profile</strong> or <strong>Mobile Device Management</strong> that you don't recognise: tap it › <strong>Remove Profile</strong>. This matters most if you ever used a "no computer" install website.</li>
</ul>

<h2>Step 4: Turn off Developer Mode (optional)</h2>
<p>If you have stopped sideloading: <em>Settings › Privacy &amp; Security › Developer Mode</em> › Off. The iPhone restarts. Any sideloaded apps still installed will not open until you turn it back on.</p>

<h2>Step 5: Secure your accounts (if something felt wrong)</h2>
<ul>
  <li>Change the password of any account you signed in to inside the removed app.</li>
  <li>If you entered your Apple ID into an unknown signing service, change your Apple ID password. Then review signed-in devices at <em>Settings › [your name]</em>.</li>
  <li>Check <em>Settings › [your name] › Sign-In &amp; Security</em> for app-specific passwords you didn't create.</li>
</ul>

<h2>Removing the signing tool itself</h2>
<p>Delete AltStore or SideStore like any app. On the computer, quit and uninstall AltServer. If you used SideStore, you can also remove its VPN app and the VPN configuration under <em>Settings › General › VPN &amp; Device Management › VPN</em>.</p>
<p>Want to reinstall later? Start with <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">how to install IPA on iPhone</a>.</p>
