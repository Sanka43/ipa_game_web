<!-- ═══ HERO ═══ -->
<section class="hero" id="hero" data-hero>
  <div class="hero-bg" aria-hidden="true">
    <?php foreach ($hero as $i => $h): ?>
      <div class="hero-bg-img<?= $i === 0 ? ' on' : '' ?>" style="background-image:url('<?= e(img($h['shots'][0] ?? $h['icon'], '1400x0w')) ?>')"></div>
    <?php endforeach; ?>
    <div class="hero-shade"></div>
  </div>
  <div class="letterbox top" aria-hidden="true"></div>
  <div class="letterbox bottom" aria-hidden="true"></div>

  <div class="wrap hero-in">
    <div class="hero-copy">
      <?php if (is_halloween()): ?>
      <div class="halloween-pill reveal">
        <span class="halloween-pill-icon">🎃</span>
        <span><b>Halloween Season Live</b> · Spooky &amp; Trending IPA Games</span>
        <span class="halloween-pill-ghost">👻</span>
      </div>
      <?php endif; ?>
      <h1 class="hero-title reveal">
        <span class="line">Free IPA Games</span>
        <span class="line grad">for iPhone &amp; iPad</span>
      </h1>
      <p class="hero-sub reveal">Download free iOS games with everything you need to know first: verified IPA files, version history, file sizes, minimum iOS requirements (iOS 15–26), and gameplay screenshots. 100% no jailbreak needed – install with the IPA Game Store app, LiveContainer, AltStore, or SideStore.</p>
      <div class="hero-cta reveal">
        <a class="btn btn-primary" href="<?= url('download-ipastore/') ?>">Download IPA Game Store</a>
        <a class="btn btn-ghost" href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">▶ How to install IPA</a>
      </div>
      <div class="hero-stats reveal">
        <span><b><?= number_format($total) ?>+</b> Free Games</span>
        <span class="dot">•</span>
        <span>Updated <b><?= e($latestDate) ?></b></span>
        <span class="dot">•</span>
        <span>iOS 15 – 26 Supported</span>
        <span class="dot">•</span>
        <span>No Jailbreak Required</span>
      </div>
      <?php
      $socialIcons = [
          'telegram' => ['Telegram', '<path d="M21.9 4.3 18.7 19.4c-.2 1.1-.9 1.3-1.8.8l-4.9-3.6-2.4 2.3c-.3.3-.5.5-1 .5l.3-5 9.1-8.2c.4-.4-.1-.6-.6-.2L6.2 13l-4.8-1.5c-1-.3-1.1-1 .2-1.5L20.5 2.8c.9-.3 1.6.2 1.4 1.5Z"/>'],
          'x'        => ['X', '<path d="M17.8 2.5h3.3l-7.2 8.2 8.5 10.8h-6.6l-5.2-6.8-6 6.8H1.3l7.7-8.8L.9 2.5h6.8l4.7 6.2 5.4-6.2Zm-1.2 17h1.8L7.1 4.3H5.2l11.4 15.2Z"/>'],
          'youtube'  => ['YouTube', '<path d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8ZM9.7 15.1V8.9l5.8 3.1-5.8 3.1Z"/>'],
      ];
      $social = array_filter(cfg('social') ?? [], fn($u, $k) => str_starts_with((string) $u, 'http') && isset($socialIcons[$k]), ARRAY_FILTER_USE_BOTH);
      if ($social): ?>
      <div class="hero-social reveal">
        <span>Follow us</span>
        <?php foreach ($social as $k => $u): ?>
          <a href="<?= e($u) ?>" target="_blank" rel="noopener" aria-label="<?= e(SITE_NAME . ' on ' . $socialIcons[$k][0]) ?>" title="<?= e($socialIcons[$k][0]) ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><?= $socialIcons[$k][1] ?></svg></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="hero-stage" aria-hidden="true">
      <?php foreach ($hero as $i => $h): ?>
        <div class="phones<?= $i === 0 ? ' on' : '' ?>">
          <?php foreach (array_slice($h['shots'], 0, 3) as $j => $s): ?>
            <figure class="phone p<?= $j ?>"><img src="<?= e(img($s, '600x0w')) ?>" alt="<?= e($h['name']) ?> gameplay screenshot" width="600" height="1226"<?= $i === 0 ? '' : ' loading="lazy"' ?> decoding="async"></figure>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <a class="scroll-cue" href="#latest" aria-label="Scroll to latest games"><span></span></a>
</section>

<!-- ═══ MARQUEE ═══ -->
<div class="marquee" aria-hidden="true"><div class="marquee-track">
  <?php
  $hTrack = is_halloween() ? [
      '🎃 Halloween Spooky Season', 'Free IPA Games', '🦇 Horror & Thriller IPA', 'iOS Games IPA',
      'Offline IPA Games', '👻 Trick or Treat Gaming', 'No Jailbreak Sideload', 'IPA Racing Games',
      'LiveContainer & SideStore', 'IPA Puzzle Games', '🕷️ Latest iOS Releases', 'iOS 15–26 Compatible'
  ] : [
      'Free IPA Games', 'iOS Games IPA', 'Offline IPA Games', 'No Jailbreak Sideload',
      'IPA Racing Games', 'LiveContainer & SideStore', 'IPA Puzzle Games', 'Latest iOS Releases',
      'IPA Games for iPad', 'Top Action IPA', 'iOS 15–26 Compatible'
  ];
  $hSep = is_halloween() ? '🎃' : '✦';
  for ($k = 0; $k < 2; $k++) foreach ($hTrack as $w): ?>
    <span><?= e($w) ?></span><i><?= $hSep ?></i>
  <?php endforeach; ?>
</div></div>

<!-- ═══ LATEST: film strip ═══ -->
<section class="sec" id="latest">
  <div class="wrap sec-head reveal">
    <div>
      <p class="kicker">New releases</p>
      <h2>Latest IPA Games – New iOS Game Releases</h2>
      <p class="sec-sub">Freshly updated and newly released iOS games. Every card shows the latest version and update date.</p>
    </div>
    <a class="see" href="<?= category_url('latest') ?>">See all latest IPA games →</a>
  </div>
  <div class="filmstrip" data-drag data-autoscroll>
    <?php foreach ($latest as $g): ?>
      <a class="frame reveal" href="<?= e(game_url($g)) ?>">
        <span class="frame-img"><?php if ($g['shot']): ?><img src="<?= e(img($g['shot'], '400x0w')) ?>" alt="<?= e($g['name']) ?> gameplay screenshot" width="400" height="622" loading="lazy" decoding="async"><?php endif; ?></span>
        <span class="frame-info">
          <img src="<?= e(img($g['icon'], '96x96')) ?>" alt="<?= e($g['name']) ?> icon" width="44" height="44" loading="lazy">
          <span><b><?= e($g['name']) ?></b><small><?= e(version_label($g['latest_version'])) ?> · <?= e(date_label($g['latest_release_date'])) ?></small></span>
        </span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ GENRE POSTERS ═══ -->
<section class="sec">
  <div class="wrap">
    <div class="sec-head reveal">
      <div>
        <p class="kicker">Pick a genre</p>
        <h2>Browse IPA Games by Category</h2>
        <p class="sec-sub">From high-speed racing and brain puzzles to action adventures and RPGs — explore games by your favorite genres.</p>
      </div>
      <a class="see" href="<?= url('ipa-games/') ?>">All IPA game categories →</a>
    </div>
    <div class="genre-cards">
      <?php foreach ($posterGenres as $i => $s): $c = category($s); ?>
        <a class="gnr reveal" href="<?= category_url($s) ?>" style="--d:<?= $i * 50 ?>ms">
          <span class="gnr-thumb">
            <?php if ($posters[$s]): ?><img src="<?= e(img($posters[$s], '500x0w')) ?>" alt="<?= e($c['name']) ?> IPA games for iPhone and iPad" width="56" height="56" loading="lazy" decoding="async"><?php endif; ?>
            <em><?= $c['icon'] ?></em>
          </span>
          <span class="gnr-txt"><b><?= e($c['name']) ?></b><small><?= (int) ($counts[$s] ?? 0) ?> games</small></span>
          <span class="gnr-go" aria-hidden="true">→</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ WHY US ═══ -->
<section class="sec why-sec">
  <div class="wrap">
    <div class="sec-head reveal">
      <div>
        <p class="kicker">Why us</p>
        <h2>Why Players Choose IPA Game Store</h2>
        <p class="sec-sub">Built for seamless iOS sideloading with safety, speed, and transparency in mind.</p>
      </div>
    </div>
    <div class="why-grid">
      <?php
      $why = [
          ['◎', 'Details before you download', 'Inspect full version history, verified file size, required iOS version (iOS 15–26), and gameplay screenshots before tapping download.'],
          ['↻', 'Updated regularly', 'We track new game releases, updates, and compatibility patches daily, with timestamps on every title.'],
          ['✦', 'Install help in plain English', count(guides()) . ' beginner-friendly guides covering LiveContainer, SideStore, AltStore, iPad sideloading, and error fixes.'],
          ['✓', 'Safety & integrity first', 'Learn how to verify SHA-256 checksums and digital signatures before installing. We never ask for your Apple ID credentials. Read <a href="' . guide_url('are-ipa-files-safe') . '">Are IPA files safe?</a>'],
          ['★', '100% Free to use', 'Browsing and downloading IPA files from our store is completely free. In-app purchases inside a game are clearly disclosed.'],
          ['§', 'Respect for developers', 'We strictly respect intellectual property and DMCA copyright guidelines. Rights holders can <a href="' . url('dmca/') . '">contact us for removal</a>.'],
      ];
      foreach ($why as $i => [$icon, $title, $text]): ?>
        <div class="why-card reveal" style="--d:<?= $i * 50 ?>ms">
          <span class="why-ico" aria-hidden="true"><?= $icon ?></span>
          <h3><?= e($title) ?></h3>
          <p><?= $text ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ TOP 10 ═══ -->
<section class="sec top10-sec">
  <div class="wrap">
    <div class="sec-head reveal">
      <div>
        <p class="kicker">Most downloaded</p>
        <h2>Top 10 Most Downloaded IPA Games</h2>
        <p class="sec-sub">The highest rated and most played iOS games, verified for smooth performance on iPhone and iPad.</p>
      </div>
      <a class="see" href="<?= category_url('iphone') ?>">Full iPhone games chart →</a>
    </div>
    <ol class="top10">
      <?php foreach ($top as $i => $g): ?>
        <li class="reveal" style="--d:<?= $i * 50 ?>ms"><a href="<?= e(game_url($g)) ?>">
          <span class="rank"><?= $i + 1 ?></span>
          <img src="<?= e(img($g['icon'], '160x160')) ?>" alt="<?= e($g['name']) ?> icon" width="72" height="72" loading="lazy">
          <span class="t10-info"><b><?= e($g['name']) ?></b><small><?= e(category($g['category'])['name'] ?? '') ?> · <?= (int) $g['dl'] > 0 ? compact_num($g['dl']) . ' downloads' : compact_num($g['rating_count']) . ' ratings' ?></small></span>
          <span class="t10-rate">★ <?= number_format((float) $g['rating_value'], 1) ?></span>
        </a></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- ═══ KEYWORD SHELVES ═══ -->
<section class="sec">
  <div class="wrap shelves3">
    <?php foreach ($shelves as $s => $games): $c = category($s); ?>
      <div class="shelf reveal">
        <div class="sec-head"><h2 class="h3"><?= e($c['h1']) ?></h2><a class="see" href="<?= category_url($s) ?>">See all <?= e($c['h1']) ?> →</a></div>
        <?php foreach ($games as $g) echo game_card($g); ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ HOW IT WORKS ═══ -->
<section class="sec how-sec">
  <div class="wrap">
    <div class="sec-head reveal">
      <div>
        <p class="kicker">How it works</p>
        <h2>How to Sideload IPA Games on iPhone in 3 Steps</h2>
        <p class="sec-sub">Install any iOS game without jailbreaking your device.</p>
      </div>
    </div>
    <ol class="how-grid">
      <li class="how-card reveal" style="--d:0ms">
        <span class="how-no">1</span>
        <h3>Choose a game</h3>
        <p>Browse our catalog and check the required iOS version (iOS 15–26), file size, and device compatibility.</p>
      </li>
      <li class="how-card reveal" style="--d:60ms">
        <span class="how-no">2</span>
        <h3>Get the IPA</h3>
        <p>Tap download for instant access to the clean, unmodified IPA package, or follow the official App Store link.</p>
      </li>
      <li class="how-card reveal" style="--d:120ms">
        <span class="how-no">3</span>
        <h3>Install &amp; play</h3>
        <p>Install using the <a href="<?= url('download-ipastore/') ?>">IPA Game Store</a> app, or sideload via <a href="<?= guide_url('install-ipa-with-livecontainer') ?>">LiveContainer</a>, <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a>, or <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> without jailbreak.</p>
      </li>
    </ol>
  </div>
</section>

<?php if (!empty($featuredGuides)): ?>
<!-- ═══ IPA ACADEMY ═══ -->
<section class="sec academy-sec">
  <div class="wrap">
    <div class="sec-head reveal">
      <div>
        <p class="kicker">IPA Academy</p>
        <h2>Learn to Install and Sideload IPA Games</h2>
        <p class="sec-sub">New to IPA files? These step-by-step guides walk you through installing, bypassing the 3-app limit, and fixing errors.</p>
      </div>
      <a class="see" href="<?= url('guides/') ?>">All <?= count(guides()) ?> guides →</a>
    </div>
    <div class="academy-grid">
      <?php $gn = 0; foreach ($featuredGuides as $s => $g): $gn++; ?>
        <a class="acard reveal" href="<?= guide_url($s) ?>" style="--d:<?= ($gn - 1) * 50 ?>ms">
          <span class="acard-no"><?= str_pad((string) $gn, 2, '0', STR_PAD_LEFT) ?></span>
          <span class="tag"><?= e($g['topic']) ?></span>
          <b><?= e($g['short']) ?></b>
          <span><?= e($g['desc']) ?></span>
          <em>Read guide →</em>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ DEVICES ═══ -->
<section class="sec">
  <div class="wrap devices">
    <a class="device reveal" href="<?= category_url('iphone') ?>">
      <img class="device-img iphone" src="<?= asset('img/gate-iphone.webp') ?>" alt="IPA games on iPhone" width="215" height="440" loading="lazy">
      <span><p class="kicker">iPhone</p><b>IPA Games for iPhone</b><small>Ranked by player popularity. Verified download sizes and minimum iOS requirements (iOS 15–26) listed for each title.</small></span>
    </a>
    <a class="device reveal" href="<?= category_url('ipad') ?>">
      <img class="device-img ipad" src="<?= asset('img/gate-ipad.webp') ?>" alt="IPA games on iPad" width="469" height="360" loading="lazy">
      <span><p class="kicker">iPad</p><b>IPA Games for iPad</b><small>High-definition titles optimized for larger Retina displays, with native iPadOS support and tablet screenshots.</small></span>
    </a>
  </div>
</section>

<!-- ═══ IPA APPS BANNER ═══ -->
<section class="sec apps-banner-sec">
  <div class="wrap">
    <div class="apps-banner reveal">
      <div class="apps-banner-copy">
        <p class="kicker">Beyond games</p>
        <h2>Explore IPA Apps for iPhone &amp; iPad</h2>
        <p>Looking for emulators, productivity tools, media players, or utilities? Browse our directory of iOS IPA applications ready for sideloading on iOS 15–26.</p>
      </div>
      <a class="btn btn-primary" href="<?= url('ipa-apps/') ?>">Explore IPA Apps →</a>
    </div>
  </div>
</section>

<!-- ═══ SEO COPY + FAQ ═══ -->
<section class="sec seo-sec">
  <div class="wrap">
    <div class="seo-intro reveal">
      <p class="kicker">About the store</p>
      <h2>Your Trusted IPA Store for Free iOS Games &amp; Sideloading</h2>
      <p>An <strong>IPA file</strong> (iOS App Store Package) is the standard file format used by every iPhone and iPad app and game. IPA Game Store curates over <strong><?= number_format($total) ?>+ free iOS games</strong> in one centralized, verified directory. Every game page gives you upfront access to the latest release versions, download file sizes, supported iOS versions (iOS 15–26), gameplay screenshots, and complete version changelogs — so you know exactly what you are installing before downloading.</p>
    </div>

    <div class="seo-grid">
      <article class="seo-card reveal" style="--d:0ms">
        <span class="seo-ico" aria-hidden="true">⌕</span>
        <h3>Find the right game fast</h3>
        <p>Browse easily by genre, including <a href="<?= category_url('racing') ?>">IPA racing games</a>, <a href="<?= category_url('puzzle') ?>">IPA puzzle games</a>, <a href="<?= category_url('action') ?>">action</a>, <a href="<?= category_url('strategy') ?>">strategy</a>, and <a href="<?= category_url('role-playing') ?>">role-playing</a>. Need something for offline entertainment during travel? Browse our handpicked <a href="<?= category_url('offline') ?>">offline IPA games</a> catalog. To see what was just added this week, check the <a href="<?= category_url('latest') ?>">latest IPA games</a>.</p>
      </article>
      <article class="seo-card reveal" style="--d:60ms">
        <span class="seo-ico" aria-hidden="true">▯</span>
        <h3>Built for iPhone &amp; iPad</h3>
        <p>Explore the <a href="<?= category_url('iphone') ?>">iPhone games</a> chart for the most downloaded titles, or switch to the <a href="<?= category_url('ipad') ?>">iPad games</a> directory for widescreen tablet experiences. If you love classic retro titles, explore our curated <a href="<?= category_url('emulator') ?>">emulator IPA apps for iOS</a>.</p>
      </article>
      <article class="seo-card reveal" style="--d:120ms">
        <span class="seo-ico" aria-hidden="true">✦</span>
        <h3>New to IPA sideloading?</h3>
        <p>Get started with our guide on <a href="<?= guide_url('what-is-an-ipa-file') ?>">what an IPA file is</a>, then follow the complete <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">iPhone install guide</a>. Want to install directly without a PC or Mac? Follow <a href="<?= guide_url('install-ipa-without-computer') ?>">how to install IPA without a computer</a> and learn how to bypass the 3-app limit with <a href="<?= guide_url('install-ipa-with-livecontainer') ?>">LiveContainer</a>.</p>
      </article>
      <article class="seo-card reveal" style="--d:180ms">
        <span class="seo-ico" aria-hidden="true">⇄</span>
        <h3>IPA vs App Store</h3>
        <p>The official Apple App Store provides automated installations. Sideloading IPA files gives you the freedom to preserve specific game versions, access regional exclusives, or play retro classics. Read our breakdown on <a href="<?= guide_url('ipa-vs-app-store') ?>">IPA vs App Store</a> to choose what fits your needs best.</p>
      </article>
      <article class="seo-card seo-safe reveal" style="--d:240ms">
        <span class="seo-ico" aria-hidden="true">✓</span>
        <h3>Safe &amp; transparent sideloading</h3>
        <p>Security is paramount: only install IPA packages from reputable directories, and learn <a href="<?= guide_url('how-to-verify-ipa-file') ?>">how to verify an IPA checksum</a>. We never ask for your Apple ID credentials or private keys. Review our <a href="<?= guide_url('are-ipa-files-safe') ?>">IPA safety checklist</a> to stay protected.</p>
      </article>
    </div>

    <div class="seo-faq"><?= faq_block($faq) ?></div>
  </div>
</section>