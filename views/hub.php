<section class="pagehead">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <h1 class="reveal">IPA Games</h1>
    <p class="lead reveal"><?= number_format($total) ?> iOS games for iPhone and iPad. Pick a category below, or scroll down for the most-played games. Every game has its own page with the version, the file size, iOS compatibility and install steps.</p>
    <div class="chips reveal">
      <?php foreach (['latest', 'offline', 'iphone', 'ipad', 'emulator'] as $s): $c = category($s); ?>
        <a class="chip" href="<?= category_url($s) ?>"><?= $c['icon'] ?> <?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($page === 1): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>Browse by genre</h2></div>
    <div class="posters posters-all">
      <?php foreach (genres() as $i => $s): $c = category($s); ?>
        <a class="poster reveal" href="<?= category_url($s) ?>" style="--d:<?= ($i % 6) * 50 ?>ms">
          <?php if ($posters[$s]): ?><img src="<?= e(img($posters[$s], '400x0w')) ?>" alt="" loading="lazy"><?php endif; ?>
          <span class="poster-shade"></span>
          <span class="poster-txt"><em><?= $c['icon'] ?></em><b><?= e($c['name']) ?></b><small><?= (int) ($counts[$s] ?? 0) ?> games</small></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2><?= $page > 1 ? "Most played iOS games — page $page" : 'Most played iOS games' ?></h2><small class="muted"><?= number_format($total) ?> games</small></div>
    <?= game_grid($games) ?>
    <?= pager($page, $pages, $base) ?>
  </div>
</section>

<?php if ($page === 1): ?>
<div class="wrap"><?= guide_cards(['how-to-install-ipa-on-iphone', 'install-ipa-with-altstore', 'install-ipa-with-sidestore', 'are-ipa-files-safe']) ?></div>
<section class="sec"><div class="wrap prose-wrap"><?= faq_block($faq) ?></div></section>
<?php endif; ?>
