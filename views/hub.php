<?php
$isApp = $kind === 'app';
$Noun  = $isApp ? 'Apps' : 'Games';
$noun  = strtolower($Noun);
$one   = $isApp ? 'app' : 'game';
$specials = $isApp ? ['latest'] : ['latest', 'offline', 'iphone', 'ipad', 'emulator'];
$genreList = $isApp ? app_genres() : genres();
?>
<section class="pagehead">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <h1 class="reveal">IPA <?= $Noun ?></h1>
    <p class="lead reveal"><?= number_format($total) ?> iOS <?= $noun ?> for iPhone and iPad. Pick a category below, or scroll down for the most-played <?= $noun ?>. Every <?= $one ?> has its own page with the version, the file size, iOS compatibility and install steps.</p>
    <div class="chips reveal">
      <?php foreach (($isApp ? nav_app_categories($specials) : nav_categories($specials)) as $s): $c = category($s, $kind); ?>
        <a class="chip" href="<?= category_url($s, $kind) ?>"><?= $c['icon'] ?> <?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($page === 1): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>Browse by genre</h2></div>
    <div class="posters posters-all">
      <?php foreach (($isApp ? nav_app_categories($genreList) : nav_categories($genreList)) as $i => $s): $c = category($s, $kind); ?>
        <a class="poster reveal" href="<?= category_url($s, $kind) ?>" style="--d:<?= ($i % 6) * 50 ?>ms">
          <?php if ($posters[$s]): ?><img src="<?= e(img($posters[$s], '400x0w')) ?>" alt="" width="400" height="533" loading="lazy" decoding="async"><?php endif; ?>
          <span class="poster-shade"></span>
          <span class="poster-txt"><em><?= $c['icon'] ?></em><b><?= e($c['name']) ?></b><small><?= (int) ($counts[$s] ?? 0) ?> <?= $noun ?></small></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2><?= $page > 1 ? "Most played iOS $noun — page $page" : "Most played iOS $noun" ?></h2><small class="muted"><?= number_format($total) ?> <?= $noun ?></small></div>
    <?= game_grid($games) ?>
    <?= pager($page, $pages, $base) ?>
  </div>
</section>

<?php if ($page === 1): ?>
<div class="wrap"><?= guide_cards(['how-to-install-ipa-on-iphone', 'install-ipa-with-altstore', 'install-ipa-with-sidestore', 'are-ipa-files-safe']) ?></div>
<div class="wrap"><div id="container-911b8b732262ab1a6da784c794f8780e"></div></div>
<section class="sec"><div class="wrap prose-wrap"><?= faq_block($faq) ?></div></section>
<?php endif; ?>
