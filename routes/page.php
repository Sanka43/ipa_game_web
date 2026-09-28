<?php
/** @var string $pageSlug */
$pages = [
    'about' => ['About ' . SITE_NAME, 'What ' . SITE_NAME . ' is, where our game data comes from, and how we keep it up to date.', '
        <p>' . SITE_NAME . ' is a catalogue of iOS games for iPhone and iPad. For every game we publish the current version, the file size, the minimum iOS version, screenshots and the full update history, together with step-by-step <a href="' . url('guides/') . '">guides</a> for installing IPA files.</p>
        <h2>Where the data comes from</h2>
        <p>Game details come from public App Store listings and from developers. Download buttons point to the official App Store whenever the game is available there. That is the safest and fastest way to install it.</p>
        <h2>Our rules</h2>
        <ul><li>We do not host or link to cracked, pirated or modified copies of paid games.</li><li>Third-party IPA files are listed only when the developer allows distribution.</li><li>Every report is reviewed. See our <a href="' . url('dmca/') . '">DMCA policy</a>.</li></ul>'],
    'dmca' => ['DMCA & Content Removal', 'How rights holders can request removal of content from ' . SITE_NAME . '.', '
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
