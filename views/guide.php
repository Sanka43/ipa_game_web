<div class="readbar" id="readbar" aria-hidden="true"><i></i></div>

<section class="pagehead guide-head">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap narrow">
    <?= breadcrumbs($crumbs) ?>
    <p class="kicker reveal"><span class="tag"><?= e($guide['topic']) ?></span> <?= $minutes ?> min read · Updated <?= e(date_label($updated)) ?></p>
    <h1 class="reveal"><?= e($guide['title']) ?></h1>
    <p class="lead reveal"><?= e($guide['desc']) ?></p>
  </div>
</section>

<div class="wrap guide-layout">
  <aside class="guide-side">
    <?php if (count($toc) > 2): ?>
    <nav class="toc sticky" aria-label="Contents">
      <p class="h3">Contents</p>
      <?php foreach ($toc as [$id, $label]): ?><a href="#<?= e($id) ?>"><?= e($label) ?></a><?php endforeach; ?>
    </nav>
    <?php endif; ?>
  </aside>

  <article class="prose guide-body">
    <?= $body ?>
    <?php if ($faq): ?><?= faq_block($faq) ?><?php endif; ?>

    <div class="guide-next">
      <p class="kicker">Keep reading</p>
      <div class="gstrip">
        <?php foreach ($related as $s): $r = guide($s); if (!$r) continue; ?>
          <a class="gdcard" href="<?= guide_url($s) ?>"><span class="tag"><?= e($r['topic']) ?></span><b><?= e($r['short']) ?></b><span><?= e(excerpt($r['desc'], 90)) ?></span></a>
        <?php endforeach; ?>
      </div>
    </div>
  </article>
</div>

<?php if ($picks): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>Games to try: offline IPA picks</h2><a class="see" href="<?= category_url('offline') ?>">All offline games →</a></div>
    <?= game_grid($picks) ?>
  </div>
</section>
<?php endif; ?>
