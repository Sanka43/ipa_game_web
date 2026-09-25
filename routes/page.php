<?php
/** @var string $pageSlug */
$pages = [
    'about' => ['About IPA Store', 'What IPA Store is, where our game data comes from, and how we keep it up to date.', '
        <p>' . SITE_NAME . ' is a catalogue of iOS games for iPhone and iPad. For every game we publish the current version, the file size, the minimum iOS version, screenshots and the full update history, together with step-by-step <a href="' . url('guides/') . '">guides</a> for installing IPA files.</p>
        <h2>Where the data comes from</h2>
        <p>Game details come from public App Store listings and from developers. Download buttons point to the official App Store whenever the game is available there. That is the safest and fastest way to install it.</p>
        <h2>Our rules</h2>
        <ul><li>We do not host or link to cracked, pirated or modified copies of paid games.</li><li>Third-party IPA files are listed only when the developer allows distribution.</li><li>Every report is reviewed. See our <a href="' . url('dmca/') . '">DMCA policy</a>.</li></ul>'],
    'dmca' => ['DMCA & Content Removal', 'How rights holders can request removal of content from IPA Store.', '
        <p>We respect intellectual property rights. If you believe content on ' . SITE_NAME . ' infringes your copyright, send a notice to our contact address with:</p>
        <ol><li>Your name, organisation and contact details.</li><li>The work you believe is infringed.</li><li>The exact URL(s) on this site.</li><li>A statement that you have a good-faith belief the use is not authorised.</li><li>A statement, under penalty of perjury, that the notice is accurate and you are the rights holder or authorised to act for them.</li><li>Your physical or electronic signature.</li></ol>
        <p>We act on valid notices, usually within 48 hours, by removing the listing or the download link.</p>'],
    'disclaimer' => ['Disclaimer', 'Legal disclaimer for IPA Store.', '
        <p>' . SITE_NAME . ' is not affiliated with, endorsed by or sponsored by Apple Inc. iPhone, iPad, iOS and App Store are trademarks of Apple Inc. Game names, icons and screenshots belong to their respective developers and are shown to identify the games.</p>
        <p>Guides are provided for information only. Sideloading is done at your own risk. Always follow the developer\'s licence and the law where you live.</p>'],
    'privacy' => ['Privacy Policy', 'How IPA Store handles your data.', '
        <p>We do not require an account and do not collect personal information. We count anonymous download clicks per game to show popularity. Our hosting provider keeps standard server logs (IP address, browser, pages visited) for security, for a limited time.</p>
        <p>If we add analytics or advertising, this page will list the providers and the cookies they use.</p>'],
    'contact' => ['Contact', 'Contact IPA Store.', '
        <p>For corrections, developer requests or <a href="' . url('dmca/') . '">DMCA notices</a>, email <strong>contact@yourdomain.com</strong>.</p>'],
];
[$title, $desc, $html] = $pages[$pageSlug];
$crumbs = [['Home', url()], [$title, url("$pageSlug/")]];
render('page', compact('title', 'html', 'crumbs'), [
    'title' => $title, 'description' => $desc, 'canonical' => abs_url("$pageSlug/"), 'schema' => [breadcrumb_ld($crumbs)],
]);
