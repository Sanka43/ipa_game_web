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
      <h1 class="hero-title reveal">
        <span class="line">Free IPA Games</span>
        <span class="line grad">for iPhone &amp; iPad</span>
      </h1>
      <p class="hero-sub reveal">Download free iOS games with everything you need to know first: latest version, file size, minimum iOS, screenshots and full update history. No jailbreak needed – install with the IPA Game Store app, AltStore or SideStore.</p>
      <div class="hero-cta reveal">
        <a class="btn btn-primary" href="<?= url('download-ipastore/') ?>">Download IPA Game Store</a>
        <a class="btn btn-ghost" href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">▶ How to install IPA</a>
      </div>
      <?php
      $socialIcons = [
          'telegram' => ['Telegram', '<path d="M21.9 4.3 18.7 19.4c-.2 1.1-.9 1.3-1.8.8l-4.9-3.6-2.4 2.3c-.3.3-.5.5-1 .5l.3-5 9.1-8.2c.4-.4-.1-.6-.6-.2L6.2 13l-4.8-1.5c-1-.3-1.1-1 .2-1.5L20.5 2.8c.9-.3 1.6.2 1.4 1.5Z"/>'],
          'x'        => ['X', '<path d="M17.8 2.5h3.3l-7.2 8.2 8.5 10.8h-6.6l-5.2-6.8-6 6.8H1.3l7.7-8.8L.9 2.5h6.8l4.7 6.2 5.4-6.2Zm-1.2 17h1.8L7.1 4.3H5.2l11.4 15.2Z"/>'],
          'youtube'  => ['YouTube', '<path d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8ZM9.7 15.1V8.9l5.8 3.1-5.8 3.1Z"/>'],
      ];
      $social = array_filter(cfg('social') ?? [], fn($u, $k) => $u !== '' && isset($socialIcons[$k]), ARRAY_FILTER_USE_BOTH);
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
            <figure class="phone p<?= $j ?>"><img src="<?= e(img($s, '600x0w')) ?>" alt="<?= e($h['name']) ?> gameplay screenshot"<?= $i === 0 ? '' : ' loading="lazy"' ?> decoding="async"></figure>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <a class="scroll-cue" href="#latest" aria-label="Scroll to latest games"><span></span></a>
</section>

<!-- ═══ MARQUEE ═══ -->
<div class="marquee" aria-hidden="true"><div class="marquee-track">
  <?php for ($k = 0; $k < 2; $k++) foreach (['IPA Games', 'iOS Games IPA', 'Offline IPA Games', 'IPA Racing Games', 'IPA Puzzle Games', 'Latest IPA Games', 'IPA Games for iPad', 'Free IPA Games'] as $w): ?>
    <span><?= e($w) ?></span><i>✦</i>
  <?php endforeach; ?>
</div></div>

<!-- ═══ LATEST: film strip ═══ -->
<section class="sec" id="latest">
  <div class="wrap sec-head reveal">
    <div><p class="kicker">New releases</p><h2>Latest IPA Games – New iOS Game Releases</h2></div>
    <a class="see" href="<?= category_url('latest') ?>">See all new games →</a>
  </div>
  <div class="filmstrip" data-drag>
    <?php foreach ($latest as $g): ?>
      <a class="frame reveal" href="<?= e(game_url($g)) ?>">
        <span class="frame-img"><?php if ($g['shot']): ?><img src="<?= e(img($g['shot'], '400x0w')) ?>" alt="<?= e($g['name']) ?> screenshot" loading="lazy" decoding="async"><?php endif; ?></span>
        <span class="frame-info">
          <img src="<?= e(img($g['icon'], '96x96')) ?>" alt="" width="44" height="44" loading="lazy">
          <span><b><?= e($g['name']) ?></b><small><?= e(version_label($g['latest_version'])) ?> · <?= e(date_label($g['latest_release_date'])) ?></small></span>
        </span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ GENRE POSTERS ═══ -->
<section class="sec">
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">Pick a genre</p><h2>Browse IPA Games by Category</h2></div><a class="see" href="<?= url('ipa-games/') ?>">All categories →</a></div>
    <div class="genre-cards">
      <?php foreach ($posterGenres as $i => $s): $c = category($s); ?>
        <a class="gcard reveal" href="<?= category_url($s) ?>" style="--d:<?= $i * 50 ?>ms">
          <span class="gcard-thumb">
            <?php if ($posters[$s]): ?><img src="<?= e(img($posters[$s], '500x0w')) ?>" alt="<?= e($c['name']) ?> IPA games for iPhone and iPad" loading="lazy" decoding="async"><?php endif; ?>
            <em><?= $c['icon'] ?></em>
          </span>
          <span class="gcard-txt"><b><?= e($c['name']) ?></b><small><?= (int) ($counts[$s] ?? 0) ?> games</small></span>
          <span class="gcard-go" aria-hidden="true">→</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ WHY US ═══ -->
<section class="sec why-sec">
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">Why us</p><h2>Why Players Use IPA Game Store</h2></div></div>
    <div class="why-grid">
      <?php
      $why = [
          ['◎', 'Details before you download', 'Version history, file size, iOS requirement and screenshots on every game page.'],
          ['↻', 'Updated regularly', 'New games and new versions are added often, and the Latest list shows dates.'],
          ['✦', 'Install help in plain English', count(guides()) . ' guides cover AltStore, SideStore, iPad, no-computer installs and error fixes.'],
          ['✓', 'Safety first', 'We explain how to verify checksums and signatures, and we say when a file is not from the App Store. Read <a href="' . guide_url('are-ipa-files-safe') . '">Are IPA files safe?</a>'],
          ['★', 'Free to use', 'Browsing and downloading from the store is free. In-app purchases inside a game are noted on its page.'],
          ['§', 'Respect for developers', 'We follow the DMCA process. Rights holders can <a href="' . url('dmca/') . '">contact us</a> for removal.'],
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
    <div class="sec-head reveal"><div><p class="kicker">Most played</p><h2>Top 10 iOS Games (IPA) – Most Played</h2></div><a class="see" href="<?= category_url('iphone') ?>">Full chart →</a></div>
    <ol class="top10">
      <?php foreach ($top as $i => $g): ?>
        <li class="reveal" style="--d:<?= $i * 50 ?>ms"><a href="<?= e(game_url($g)) ?>">
          <span class="rank"><?= $i + 1 ?></span>
          <img src="<?= e(img($g['icon'], '160x160')) ?>" alt="<?= e($g['name']) ?> icon" width="72" height="72" loading="lazy">
          <span class="t10-info"><b><?= e($g['name']) ?></b><small><?= e(category($g['category'])['name'] ?? '') ?> · <?= compact_num($g['rating_count']) ?> ratings</small></span>
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
        <div class="sec-head"><h2 class="h3"><?= e($c['h1']) ?></h2><a class="see" href="<?= category_url($s) ?>">See all →</a></div>
        <?php foreach ($games as $g) echo game_card($g); ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ HOW IT WORKS ═══ -->
<section class="sec how-sec">
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">How it works</p><h2>How to Get IPA Games on Your iPhone in 3 Steps</h2></div></div>
    <ol class="how-grid">
      <li class="how-card reveal" style="--d:0ms">
        <span class="how-no">1</span>
        <h3>Choose a game</h3>
        <p>Open any game page and check the version, size and minimum iOS. Make sure your device is supported.</p>
      </li>
      <li class="how-card reveal" style="--d:60ms">
        <span class="how-no">2</span>
        <h3>Get the IPA</h3>
        <p>Tap download. Games that are on the App Store link straight to it; other IPA files come with details so you can verify them.</p>
      </li>
      <li class="how-card reveal" style="--d:120ms">
        <span class="how-no">3</span>
        <h3>Install it</h3>
        <p>Use the <a href="<?= url('download-ipastore/') ?>">IPA Game Store</a>, then install with <a href="https://www.livecontainer.site/download/" rel="noopener" target="_blank">LiveContainer</a>. No jailbreak is required. See the <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">full iPhone install guide</a>.</p>
      </li>
    </ol>
  </div>
</section>

<!-- ═══ DEVICES ═══ -->
<section class="sec">
  <div class="wrap devices">
    <a class="device reveal" href="<?= category_url('iphone') ?>">
      <img class="device-img iphone" src="<?= asset('img/gate-iphone.webp') ?>" alt="IPA games on iPhone" width="215" height="440" loading="lazy">
      <span><p class="kicker">iPhone</p><b>IPA Games for iPhone</b><small>Ranked by how many people play them. Minimum iOS and download size listed for each game.</small></span>
    </a>
    <a class="device reveal" href="<?= category_url('ipad') ?>">
      <img class="device-img ipad" src="<?= asset('img/gate-ipad.webp') ?>" alt="IPA games on iPad" width="469" height="360" loading="lazy">
      <span><p class="kicker">iPad</p><b>IPA Games for iPad</b><small>Games built for the big screen, with native iPad support and iPad screenshots.</small></span>
    </a>
  </div>
</section>

<!-- ═══ SEO COPY + FAQ ═══ -->
<section class="sec">
  <div class="wrap prose-wrap">
    <article class="prose reveal">
      <h2>Your IPA Store for Free iOS Games</h2>
      <p>An <strong>IPA file</strong> (iOS App Store Package) is the file format used by every iPhone and iPad app and game. IPA Game Store lists <strong><?= number_format($total) ?>+ free iOS games</strong> in one place. Each game page shows the current version, file size, minimum iOS version, screenshots and the full update history, so you know exactly what you are installing before you tap download.</p>
      <p><strong>Find the right game fast.</strong> Browse by genre, for example <a href="<?= category_url('racing') ?>">IPA racing games</a>, <a href="<?= category_url('puzzle') ?>">IPA puzzle games</a>, <a href="<?= category_url('action') ?>">action</a>, <a href="<?= category_url('strategy') ?>">strategy</a> or <a href="<?= category_url('role-playing') ?>">role-playing</a>. Looking for something to play on a flight? Open the <a href="<?= category_url('offline') ?>">offline IPA games</a> list. To see what just arrived, check the <a href="<?= category_url('latest') ?>">latest IPA games</a>.</p>
      <p><strong>Made for iPhone and iPad.</strong> Use the <a href="<?= category_url('iphone') ?>">iPhone games</a> chart for the most played titles, or the <a href="<?= category_url('ipad') ?>">iPad games</a> list for games with native tablet support. If you like retro gaming, see <a href="<?= category_url('emulator') ?>">emulator IPA apps for iOS</a>.</p>
      <p><strong>New to IPA files?</strong> Start with <a href="<?= guide_url('what-is-an-ipa-file') ?>">what an IPA file is</a>, then follow the <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">iPhone install guide</a>. Prefer not to use a computer? Read <a href="<?= guide_url('install-ipa-without-computer') ?>">how to install IPA without a computer</a>. If something goes wrong, <a href="<?= guide_url('ipa-installation-failed') ?>">12 fixes for a failed IPA install</a> will help.</p>
      <p><strong>IPA vs App Store.</strong> The App Store is the simplest and safest way to install apps. IPA files are useful when you want a specific older version, an app that is not available in your region, or a copy of a game. <a href="<?= guide_url('ipa-vs-app-store') ?>">Learn the difference</a> before you decide.</p>
      <p><strong>Stay safe.</strong> Only install files from sources you trust, and <a href="<?= guide_url('how-to-verify-ipa-file') ?>">verify the IPA file</a> when you can. We never ask for your Apple ID password on this website.</p>
    </article>
    <?= faq_block($faq) ?>
  </div>
</section>
