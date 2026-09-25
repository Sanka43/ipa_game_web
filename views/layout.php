<?php
/** @var string $content  @var array $meta */
$title     = $meta['title'] ?? SITE_NAME;
$fullTitle = str_contains($title, SITE_NAME) ? $title : "$title | " . SITE_NAME;
$desc      = $meta['description'] ?? cfg('tagline');
$canonical = $meta['canonical'] ?? null;
$ogImage   = $meta['og_image'] ?? abs_url('assets/img/og-default.jpg');
$bodyClass = $meta['body_class'] ?? '';
$nav       = $meta['nav'] ?? '';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<?php if (!empty($meta['robots'])): ?><meta name="robots" content="<?= e($meta['robots']) ?>">
<?php endif; if ($canonical): ?><link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<meta name="theme-color" content="#05060a">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:type" content="<?= e($meta['og_type'] ?? 'website') ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<?php if ($canonical): ?><meta property="og:url" content="<?= e($canonical) ?>">
<?php endif; if ($ogImage): ?><meta property="og:image" content="<?= e($ogImage) ?>">
<meta name="twitter:card" content="summary_large_image">
<?php endif; ?>
<link rel="icon" type="image/png" sizes="32x32" href="<?= asset('img/favicon-32.png') ?>">
<link rel="icon" type="image/png" sizes="48x48" href="<?= asset('img/favicon-48.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('img/apple-touch-icon.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://is1-ssl.mzstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Unbounded:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<?php foreach ($meta['schema'] ?? [] as $s) echo json_ld($s), "\n"; ?>
<?php if (($gaId = cfg('ga_id')) && !cfg('debug')): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config',<?= json_encode($gaId) ?>);</script>
<?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip" href="#main">Skip to content</a>
<div class="grain" aria-hidden="true"></div>

<header class="topbar" id="topbar">
  <div class="wrap topbar-in">
    <a class="brand" href="<?= url() ?>" aria-label="<?= e(SITE_NAME) ?> home">
      <img class="brand-mark" src="<?= asset('img/logo-128.webp') ?>" alt="" width="40" height="40">
      <span class="brand-text">IPA<b>STORE</b></span>
    </a>

    <nav class="mainnav" id="mainnav" aria-label="Main">
      <div class="nav-item has-mega">
        <a href="<?= url('ipa-games/') ?>" class="<?= $nav === 'games' ? 'on' : '' ?>">IPA Games</a>
        <div class="mega">
          <div class="mega-col">
            <p class="mega-h">Browse</p>
            <?php foreach (['latest', 'offline', 'iphone', 'ipad', 'emulator'] as $s): $c = category($s); ?>
              <a href="<?= category_url($s) ?>"><span><?= $c['icon'] ?></span><?= e($c['h1']) ?></a>
            <?php endforeach; ?>
          </div>
          <div class="mega-col mega-genres">
            <p class="mega-h">Genres</p>
            <?php foreach (genres() as $s): $c = category($s); ?>
              <a href="<?= category_url($s) ?>"><span><?= $c['icon'] ?></span><?= e($c['name']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <a href="<?= category_url('latest') ?>" class="<?= $nav === 'latest' ? 'on' : '' ?>">New</a>
      <a href="<?= category_url('offline') ?>" class="<?= $nav === 'offline' ? 'on' : '' ?>">Offline</a>
      <div class="nav-item has-mega">
        <a href="<?= url('guides/') ?>" class="<?= $nav === 'guides' ? 'on' : '' ?>">Guides</a>
        <div class="mega mega-guides">
          <?php foreach (guides() as $s => $g): ?>
            <a href="<?= guide_url($s) ?>"><span class="tag"><?= e($g['topic']) ?></span><?= e($g['short']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>

    <form class="topsearch" action="<?= url('search/') ?>" role="search" data-suggest>
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <input type="search" name="q" placeholder="Search IPA games…" aria-label="Search games" autocomplete="off" value="<?= e($_GET['q'] ?? '') ?>">
      <div class="suggest" hidden></div>
    </form>

    <button class="menu-btn" id="menuBtn" aria-label="Open menu" aria-expanded="false" aria-controls="mainnav"><i></i><i></i></button>
  </div>
</header>

<main id="main">
<?= $content ?>
</main>

<footer class="foot">
  <div class="wrap">
    <div class="foot-top">
      <div class="foot-brand">
        <a class="brand" href="<?= url() ?>"><img class="brand-mark" src="<?= asset('img/logo-128.webp') ?>" alt="" width="40" height="40"><span class="brand-text">IPA<b>STORE</b></span></a>
        <p>IPA games for iPhone and iPad, with versions, file sizes and install guides.</p>
      </div>
      <nav aria-label="Browse">
        <p class="foot-h">Browse</p>
        <a href="<?= url('download-ipastore/') ?>">Download IPAStore</a>
        <a href="<?= url('ipa-games/') ?>">All IPA Games</a>
        <?php foreach (['latest', 'offline', 'iphone', 'ipad', 'emulator'] as $s): ?>
          <a href="<?= category_url($s) ?>"><?= e(category($s)['h1']) ?></a>
        <?php endforeach; ?>
      </nav>
      <nav aria-label="Genres">
        <p class="foot-h">Genres</p>
        <?php foreach (['action', 'racing', 'puzzle', 'adventure', 'simulation', 'strategy', 'sports', 'role-playing'] as $s): ?>
          <a href="<?= category_url($s) ?>"><?= e(category($s)['name']) ?> IPA Games</a>
        <?php endforeach; ?>
      </nav>
      <nav aria-label="Guides">
        <p class="foot-h">Guides</p>
        <?php foreach (array_slice(guides(), 0, 7, true) as $s => $g): ?>
          <a href="<?= guide_url($s) ?>"><?= e($g['short']) ?></a>
        <?php endforeach; ?>
        <a href="<?= url('guides/') ?>">All guides →</a>
      </nav>
    </div>
    <div class="foot-bottom">
      <p>© <?= date('Y') ?> <?= e(SITE_NAME) ?>. Not affiliated with Apple Inc. App names, icons and screenshots belong to their developers. iPhone, iPad and App Store are trademarks of Apple Inc.</p>
      <nav aria-label="Legal"><a href="<?= url('about/') ?>">About</a><a href="<?= url('dmca/') ?>">DMCA</a><a href="<?= url('disclaimer/') ?>">Disclaimer</a><a href="<?= url('privacy/') ?>">Privacy</a><a href="<?= url('contact/') ?>">Contact</a></nav>
    </div>
  </div>
</footer>

<script>window.SITE_BASE = <?= json_encode(str_replace(' ', '%20', BASE_PATH)) ?>;</script>
<?php foreach ($meta['scripts'] ?? [] as $src): ?><script src="<?= e($src) ?>" defer></script>
<?php endforeach; ?>
<script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
