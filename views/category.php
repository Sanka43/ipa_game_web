<section class="pagehead has-poster">
  <?php if ($poster): ?><div class="pagehead-bg" aria-hidden="true" style="background-image:url('<?= e(img($poster, '1400x0w')) ?>')"></div><?php endif; ?>
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <p class="kicker reveal"><?= $cat['icon'] ?> <?= number_format($total) ?> games</p>
    <h1 class="reveal"><?= e($cat['h1']) ?></h1>
    <p class="lead reveal"><?= e($cat['intro']) ?></p>
    <?php if (!empty($cat['adult'])): ?><p class="badge-warn reveal">17+ · Simulated gambling</p><?php endif; ?>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if ($games): ?>
      <div class="toolbar">
        <div class="chips" role="group" aria-label="Sort">
          <?php foreach ($sorts as $k => [$label]): ?>
            <a class="chip<?= $sort === $k || (!$sort && $k === 'popular' && empty($cat['order'])) ? ' on' : '' ?>" href="<?= e($base . ($k === 'popular' ? '' : '?sort=' . $k)) ?>" rel="nofollow"><?= e($label) ?></a>
          <?php endforeach; ?>
        </div>
        <small class="muted">Page <?= $page ?> of <?= max(1, $pages) ?></small>
      </div>
      <?= game_grid($games, $page > 1) ?>
      <?= pager($page, $pages, $base) ?>
    <?php else: ?>
      <div class="empty-state reveal">
        <p class="big">◎</p>
        <h2>No games here yet</h2>
        <p>We are adding <?= e(strtolower($cat['name'])) ?> titles now. In the meantime, learn how sideloading works, so you are ready when they arrive.</p>
        <a class="btn btn-primary" href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">How to install IPA files</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<div class="wrap">
  <section class="sec-mini">
    <h2 class="h3">More categories</h2>
    <div class="chips">
      <?php foreach (array_keys(categories()) as $s): if ($s === $cat['slug']) continue; $c = category($s); ?>
        <a class="chip" href="<?= category_url($s) ?>"><?= $c['icon'] ?> <?= e($c['h1']) ?></a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php if ($page === 1): ?>
    <?= guide_cards(['how-to-install-ipa-on-iphone', 'sideload-games-on-iphone', 'install-ipa-on-ipad', 'how-to-verify-ipa-file']) ?>
    <?php if ($total): ?><div class="prose-wrap sec"><?= faq_block($faq, $cat['h1'] . ' — FAQ') ?></div><?php endif; ?>
  <?php endif; ?>
</div>
