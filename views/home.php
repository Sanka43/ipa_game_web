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
      <p class="eyebrow reveal"><span class="dot"></span> <?= number_format($total) ?> games · updated <?= date('M j') ?></p>
      <h1 class="hero-title reveal">
        <span class="line">Free IPA Games</span>
        <span class="line grad">for iPhone &amp; iPad</span>
      </h1>
      <p class="hero-sub reveal">Download iOS games with the full details: version history, file sizes, compatibility and install guides for each one.</p>
      <div class="hero-cta reveal">
        <a class="btn btn-primary" href="<?= url('download-ipastore/') ?>">Download IPAStore</a>
        <a class="btn btn-ghost" href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">▶ How to install IPA</a>
      </div>
    </div>

    <div class="hero-stage" aria-hidden="true">
      <?php foreach ($hero as $i => $h): ?>
        <div class="phones<?= $i === 0 ? ' on' : '' ?>">
          <?php foreach (array_slice($h['shots'], 0, 3) as $j => $s): ?>
            <figure class="phone p<?= $j ?>"><img src="<?= e(img($s, '600x0w')) ?>" alt="" loading="lazy"></figure>
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
    <div><p class="kicker">New releases</p><h2>Latest IPA Games</h2></div>
    <a class="see" href="<?= category_url('latest') ?>">See all new games →</a>
  </div>
  <div class="filmstrip" data-drag>
    <?php foreach ($latest as $g): ?>
      <a class="frame reveal" href="<?= e(game_url($g)) ?>">
        <span class="frame-img"><?php if ($g['shot']): ?><img src="<?= e(img($g['shot'], '400x0w')) ?>" alt="<?= e($g['name']) ?> screenshot" loading="lazy"><?php endif; ?></span>
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
    <div class="sec-head reveal"><div><p class="kicker">Pick a genre</p><h2>IPA Games by Category</h2></div><a class="see" href="<?= url('ipa-games/') ?>">All categories →</a></div>
    <div class="posters">
      <?php foreach ($posterGenres as $i => $s): $c = category($s); ?>
        <a class="poster reveal" href="<?= category_url($s) ?>" style="--d:<?= $i * 60 ?>ms">
          <?php if ($posters[$s]): ?><img src="<?= e(img($posters[$s], '500x0w')) ?>" alt="" loading="lazy"><?php endif; ?>
          <span class="poster-shade"></span>
          <span class="poster-txt"><em><?= $c['icon'] ?></em><b><?= e($c['name']) ?></b><small><?= (int) ($counts[$s] ?? 0) ?> games</small></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ TOP 10 ═══ -->
<section class="sec top10-sec">
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">Most played</p><h2>Top 10 iOS Games</h2></div><a class="see" href="<?= category_url('iphone') ?>">Full chart →</a></div>
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

<!-- ═══ GUIDES (authority layer) ═══ -->
<section class="sec academy">
  <div class="academy-bg" aria-hidden="true"></div>
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">IPA Academy</p><h2>Learn to Sideload IPA Games</h2></div><a class="see" href="<?= url('guides/') ?>">All <?= count(guides()) ?> guides →</a></div>
    <div class="academy-grid">
      <?php $n = 0; foreach (guides() as $s => $g): if ($n++ >= 6) break; ?>
        <a class="acard reveal" href="<?= guide_url($s) ?>" style="--d:<?= $n * 60 ?>ms">
          <span class="acard-no"><?= str_pad((string) $n, 2, '0', STR_PAD_LEFT) ?></span>
          <span class="tag"><?= e($g['topic']) ?></span>
          <b><?= e($g['short']) ?></b>
          <span><?= e(excerpt($g['desc'], 110)) ?></span>
          <em>Read guide →</em>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ DEVICES ═══ -->
<section class="sec">
  <div class="wrap devices">
    <a class="device reveal" href="<?= category_url('iphone') ?>">
      <span class="device-art iphone" aria-hidden="true"></span>
      <span><p class="kicker">iPhone</p><b>IPA Games for iPhone</b><small>Ranked by how many people play them. Minimum iOS and download size listed for each game.</small></span>
    </a>
    <a class="device reveal" href="<?= category_url('ipad') ?>">
      <span class="device-art ipad" aria-hidden="true"></span>
      <span><p class="kicker">iPad</p><b>IPA Games for iPad</b><small>Games built for the big screen, with native iPad support and iPad screenshots.</small></span>
    </a>
  </div>
</section>

<!-- ═══ SEO COPY + FAQ ═══ -->
<section class="sec">
  <div class="wrap prose-wrap">
    <article class="prose reveal">
      <h2>Your IPA store for iOS games</h2>
      <p>An <strong>IPA file</strong> is the package format for every iPhone and iPad game. This store lists <?= number_format($total) ?> free <strong>iOS games</strong>. Each game page gives you the current version, the file size, the minimum iOS version, screenshots and the full update history, so you know what you are installing before you tap download.</p>
      <p>Browse by genre, such as <a href="<?= category_url('racing') ?>">IPA racing games</a> or <a href="<?= category_url('puzzle') ?>">IPA puzzle games</a>. You can also find <a href="<?= category_url('offline') ?>">offline IPA games</a> for flights, or check the <a href="<?= category_url('latest') ?>">latest IPA games</a> added this week. If you have never installed an IPA, start with <a href="<?= guide_url('what-is-an-ipa-file') ?>">what an IPA file is</a>, then follow our <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">iPhone install guide</a>.</p>
    </article>
    <?= faq_block($faq) ?>
  </div>
</section>
