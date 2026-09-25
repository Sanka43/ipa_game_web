<?php
$related = ['are-ipa-files-safe', 'what-is-an-ipa-file', 'ipa-installation-failed'];
$howto = [
    ['Compare the checksum', 'Calculate the SHA-256 hash of the IPA and compare it with the one the developer published.'],
    ['Extract the IPA', 'Rename the .ipa to .zip and extract it to see the Payload folder.'],
    ['Check Info.plist', 'Read the bundle ID, version and minimum iOS from Payload/App.app/Info.plist.'],
    ['Look for injected libraries', 'Check the Frameworks folder for unexpected .dylib files.'],
    ['Inspect the signature', 'On a Mac, run codesign -dvv on the .app to see who signed it.'],
    ['Scan it', 'Upload the file to VirusTotal as an extra check.'],
];
$faq = [
    ['What is a checksum?', 'A checksum (such as SHA-256) is a fingerprint of a file. If even one byte changes, the fingerprint changes completely. Matching checksums prove you have exactly the file the developer published.'],
    ['Can I verify an IPA on my iPhone?', 'Only partly. The Files app can unzip archives, but checksums and signature checks are much easier on a computer.'],
    ['Does a VirusTotal scan mean an IPA is safe?', 'No. Most antivirus engines are poor at iOS code. A clean scan is a small plus, while a detection is a strong warning.'],
];
?>
<p>A few minutes of checking can tell you whether an IPA is the file its developer actually published, and whether someone has added code to it. You need a computer for the best checks. The commands below work on Windows and Mac.</p>

<h2>1. Compare the SHA-256 checksum</h2>
<p>If the developer publishes a checksum (GitHub releases often do), compare it with yours:</p>
<p><strong>Windows (PowerShell):</strong></p>
<pre><code>Get-FileHash .\Game.ipa -Algorithm SHA256</code></pre>
<p><strong>Windows (Command Prompt):</strong></p>
<pre><code>certutil -hashfile Game.ipa SHA256</code></pre>
<p><strong>Mac / Linux:</strong></p>
<pre><code>shasum -a 256 Game.ipa</code></pre>
<p>If the hashes match exactly, the file has not been changed and was not corrupted during the download. If they don't match, <strong>do not install it</strong>.</p>

<h2>2. Look inside the IPA</h2>
<p>An IPA is a ZIP file. Copy it, rename the copy to <code>.zip</code>, and extract it. You should see <code>Payload/GameName.app/</code>. If the archive contains other odd top-level files, such as <code>.exe</code> or <code>.bat</code> files or installers, delete it.</p>

<h2>3. Check the bundle ID and version</h2>
<p>Open <code>Payload/GameName.app/Info.plist</code>. On a Mac:</p>
<pre><code>plutil -p Payload/GameName.app/Info.plist | grep -E "CFBundleIdentifier|CFBundleShortVersionString|MinimumOSVersion"</code></pre>
<p>On Windows, with Python installed (the plist is often binary):</p>
<pre><code>python -c "import plistlib;d=plistlib.load(open(r'Payload\GameName.app\Info.plist','rb'));print(d['CFBundleIdentifier'],d.get('CFBundleShortVersionString'),d.get('MinimumOSVersion'))"</code></pre>
<p>Make sure the <strong>bundle ID</strong> belongs to the expected developer (for example <code>com.realdeveloper.game</code>) and the version is the one you meant to download.</p>

<h2>4. Look for injected libraries</h2>
<p>Open <code>Payload/GameName.app/Frameworks/</code>. Well-known frameworks, such as analytics SDKs, Unity and ad networks, are normal. Be suspicious of:</p>
<ul>
  <li>loose <code>.dylib</code> files with random or "tweak"-style names,</li>
  <li>names like <code>hack</code>, <code>menu</code>, <code>inject</code>, <code>substrate</code> or <code>cheat</code>,</li>
  <li>files dated much later than everything else.</li>
</ul>
<p>On a Mac, you can list what the main program loads:</p>
<pre><code>otool -L Payload/GameName.app/GameName</code></pre>

<h2>5. Inspect the code signature (Mac)</h2>
<pre><code>codesign -dvv Payload/GameName.app
codesign -d --entitlements - Payload/GameName.app</code></pre>
<p>The first command shows who signed the app. The second lists what it is allowed to do. A simple game rarely needs unusual entitlements such as VPN configuration or access to other apps' shared data.</p>

<h2>6. Scan with VirusTotal</h2>
<p>Upload the IPA to <strong>virustotal.com</strong>. It takes files up to 650 MB. A clean result is only a weak signal, but a detection by several engines is a clear reason to walk away.</p>

<h2>Verification checklist</h2>
<ul class="checks">
  <li class="ok">Checksum matches the developer's published value</li>
  <li class="ok">Only <code>Payload/</code> inside the archive</li>
  <li class="ok">Bundle ID matches the real developer</li>
  <li class="ok">No unexplained <code>.dylib</code> files</li>
  <li class="ok">Entitlements make sense for a game</li>
</ul>
<p>Passed all checks? Continue with <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">how to install IPA on iPhone</a>.</p>
