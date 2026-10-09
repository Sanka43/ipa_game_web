<?php
/** @var string $pageSlug */
$pages = [
    'about' => ['About ' . SITE_NAME, 'What ' . SITE_NAME . ' is, where our game data comes from, and how we keep it up to date.', '
        <p>' . SITE_NAME . ' is a catalogue of iOS games for iPhone and iPad. For every game we publish the current version, the file size, the minimum iOS version, screenshots and the full update history, together with step-by-step <a href="' . url('guides/') . '">guides</a> for installing IPA files.</p>
        <h2>Where the data comes from</h2>
        <p>Game details come from public App Store listings and from developers. Download buttons point to the official App Store whenever the game is available there. That is the safest and fastest way to install it.</p>
        <h2>Our rules</h2>
        <ul><li>We do not host or link to cracked, pirated or modified copies of paid games.</li><li>Third-party IPA files are listed only when the developer allows distribution.</li><li>Every report is reviewed. See our <a href="' . url('dmca/') . '">DMCA policy</a>.</li></ul>'],
    'terms' => ['Terms of Service', 'The rules for using ' . SITE_NAME . ': what we provide, what we do not, and your responsibilities.', '
        <p>By using ' . SITE_NAME . ' you agree to these terms. If you do not agree, please do not use the site.</p>
        <h2>What we provide</h2>
        <p>' . SITE_NAME . ' is an information site about iOS games. We list game details, link to official App Store pages, and, where we have the right to, offer IPA files. Everything is provided <strong>as is</strong>, without warranty.</p>
        <h2>Your responsibilities</h2>
        <ul><li>Install apps only on devices you own or are allowed to manage.</li><li>Follow the developer\'s licence and the law where you live.</li><li>Verify files before installing. See <a href="' . url('how-we-verify/') . '">how we check files</a>.</li><li>Do not use the site to distribute pirated or modified copies of paid games.</li></ul>
        <h2>Risks of sideloading</h2>
        <p>Installing an IPA outside the App Store bypasses Apple\'s review. Signed apps need to be refreshed, can stop working, and are installed at your own risk. We are not liable for loss of data, account bans or device problems.</p>
        <h2>Third-party content</h2>
        <p>Game names, icons and screenshots belong to their developers. Links to other sites are not under our control. See the <a href="' . url('disclaimer/') . '">disclaimer</a>.</p>
        <h2>Copyright</h2>
        <p>Rights holders can request removal at any time. See the <a href="' . url('dmca/') . '">copyright and takedown policy</a>.</p>
        <h2>Changes</h2>
        <p>We may update these terms. The current version is always on this page. Questions: <a href="mailto:info@ipagame.store">info@ipagame.store</a>.</p>'],
    'how-we-verify' => ['How We Check Files', 'How ' . SITE_NAME . ' checks game files and sources, and what you should verify before installing an IPA.', '
        <p>This page explains what we check, what we cannot check, and what you should do yourself before installing anything.</p>
        <h2>What we check</h2>
        <ul><li><strong>Source and permission.</strong> Every download page states where the file comes from and why we may share it: official App Store release, free from the developer, open source, or authorised by the developer.</li><li><strong>Game details.</strong> Version, size, minimum iOS and developer are taken from the App Store listing or the developer.</li><li><strong>Checksum.</strong> When we host a file we publish its SHA-256, so you can confirm your copy is identical.</li><li><strong>Last verified date.</strong> Shown on each page when a person has reviewed the entry. "Not yet reviewed" means it has not been reviewed.</li></ul>
        <h2>What we do not claim</h2>
        <p>A checksum proves a file is unchanged. It does not prove the file is harmless, and we never call a file "100% safe". We do not accept files we cannot confirm the rights to.</p>
        <h2>What to verify before installing</h2>
        <ol><li>Prefer the App Store version when it exists.</li><li>Compare the SHA-256 with the value on the download page. See <a href="' . guide_url('how-to-verify-ipa-file') . '">how to verify an IPA file</a>.</li><li>Check the bundle ID and developer match the real game.</li><li>Avoid "modded" files that promise unlimited coins. Read <a href="' . guide_url('are-ipa-files-safe') . '">are IPA files safe?</a></li></ol>
        <h2>Found a problem?</h2>
        <p>Email <a href="mailto:info@ipagame.store">info@ipagame.store</a> or send a <a href="' . url('dmca/') . '">takedown notice</a>. We review every report.</p>'],
    'dmca' => ['Copyright & DMCA Takedown Policy', 'How rights holders can request removal of content from ' . SITE_NAME . '.', '
        <p>We respect intellectual property rights. If you believe content on ' . SITE_NAME . ' infringes your copyright, send a notice to our contact address with:</p>
        <ol><li>Your name, organisation and contact details.</li><li>The work you believe is infringed.</li><li>The exact URL(s) on this site.</li><li>A statement that you have a good-faith belief the use is not authorised.</li><li>A statement, under penalty of perjury, that the notice is accurate and you are the rights holder or authorised to act for them.</li><li>Your physical or electronic signature.</li></ol>
        <p>We act on valid notices, usually within 48 hours, by removing the listing or the download link.</p>'],
    'disclaimer' => ['Disclaimer', 'Legal disclaimer for ' . SITE_NAME . '.', '
        <p>' . SITE_NAME . ' is not affiliated with, endorsed by or sponsored by Apple Inc. iPhone, iPad, iOS and App Store are trademarks of Apple Inc. Game names, icons and screenshots belong to their respective developers and are shown to identify the games.</p>
        <p>Guides are provided for information only. Sideloading is done at your own risk. Always follow the developer\'s licence and the law where you live.</p>'],
    'privacy' => ['Privacy Policy', 'How ' . SITE_NAME . ' handles your data: analytics, cookies and server logs.', '
        <p>We do not require an account and do not ask for your name, email or any other personal details. This page explains what data is collected when you use ' . SITE_NAME . '.</p>
        <h2>Analytics (only with your consent)</h2>
        <p>If you click <strong>Accept</strong> in the cookie banner, we load two analytics tools. They help us see which pages are useful and where the site is hard to use:</p>
        <ul>
          <li><strong>Google Analytics 4</strong> (Google LLC): pages visited, time on page, device and browser type, approximate location from your IP address. Cookies: <code>_ga</code>, <code>_ga_*</code>, kept for up to 2 years. <a href="https://policies.google.com/privacy" rel="nofollow noopener" target="_blank">Google privacy policy</a>.</li>
          <li><strong>Microsoft Clarity</strong> (Microsoft Corporation): clicks, scrolling and anonymised session recordings. Text you type is masked. Cookies: <code>_clck</code> (1 year), <code>_clsk</code> (1 day), <code>CLID</code> (1 year). <a href="https://privacy.microsoft.com/privacystatement" rel="nofollow noopener" target="_blank">Microsoft privacy statement</a>.</li>
        </ul>
        <p>If you click <strong>Decline</strong>, or ignore the banner, neither tool loads and no analytics cookies are set. Your choice is saved in your browser only. To change it, use the <strong>Cookie settings</strong> link at the bottom of every page.</p>
        <h2>Download counts</h2>
        <p>We count anonymous download clicks per game to show popularity. Nothing is stored about who clicked.</p>
        <h2>Server logs</h2>
        <p>Our hosting provider keeps standard server logs (IP address, browser, pages visited) for security, for a limited time.</p>
        <h2>Contact</h2>
        <p>Questions about your data: <a href="mailto:info@ipagame.store">info@ipagame.store</a>.</p>'],
    'contact' => ['Contact', 'Contact ' . SITE_NAME . ' for corrections, developer requests and DMCA notices.', '
        <p>For corrections, developer requests or <a href="' . url('dmca/') . '">DMCA notices</a>, email <a href="mailto:info@ipagame.store"><strong>info@ipagame.store</strong></a>.</p>'],
];
[$title, $desc, $html] = $pages[$pageSlug];
$crumbs = [['Home', url()], [$title, url("$pageSlug/")]];
render('page', compact('title', 'html', 'crumbs'), [
    'title' => $title, 'description' => $desc, 'canonical' => abs_url("$pageSlug/"), 'schema' => [breadcrumb_ld($crumbs)],
]);
