<section class="pagehead">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap">
    <h1><?= $q !== '' ? 'Results for “' . e($q) . '”' : 'Search IPA games' ?></h1>
    <form class="bigsearch" action="<?= url('search/') ?>" role="search" data-suggest>
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Game name or developer…" aria-label="Search" autofocus autocomplete="off">
      <button class="btn btn-primary">Search</button>
      <div class="suggest" hidden></div>
    </form>
    <?php if ($q !== ''): ?><p class="muted"><?= number_format($total) ?> games found</p><?php endif; ?>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if ($guideHits): ?>
      <div class="gstrip" style="margin-bottom:32px">
        <?php foreach ($guideHits as $s => $g): ?>
          <a class="gdcard" href="<?= guide_url($s) ?>"><span class="tag">Guide</span><b><?= e($g['short']) ?></b><span><?= e(excerpt($g['desc'], 90)) ?></span></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($games): ?>
      <?= game_grid($games) ?>
      <?= pager($page, $pages, url('search/') . '?q=' . rawurlencode($q)) ?>
    <?php elseif ($q !== ''): ?>
      <div class="empty-state"><h2>No games match “<?= e($q) ?>”</h2><p>Try a shorter name, or browse <a href="<?= url('ipa-games/') ?>">all IPA games</a>.</p></div>
    <?php else: ?>
      <div class="chips"><?php foreach (array_keys(categories()) as $s): $c = category($s); ?><a class="chip" href="<?= category_url($s) ?>"><?= $c['icon'] ?> <?= e($c['h1']) ?></a><?php endforeach; ?></div>
    <?php endif; ?>
  </div>
</section>
