<section class="pagehead academy-head">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <p class="kicker reveal">IPA Academy · <?= count(guides()) ?> guides</p>
    <h1 class="reveal">IPA Guides</h1>
    <p class="lead reveal">Everything you need to sideload games on iPhone and iPad: what an IPA file is, every working install method, how to check that a file is safe, and how to fix errors. New to IPA? Start with the first guide.</p>
    <a class="btn btn-primary reveal" href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">Start here: Install IPA on iPhone →</a>
  </div>
</section>

<div class="wrap">
  <?php $n = 0; foreach ($topics as $key => $label): if (empty($byTopic[$key])) continue; ?>
    <section class="sec">
      <div class="sec-head"><h2><?= e($label) ?></h2></div>
      <div class="academy-grid">
        <?php foreach ($byTopic[$key] as $s => $g): $n++; ?>
          <a class="acard reveal" href="<?= guide_url($s) ?>">
            <span class="acard-no"><?= str_pad((string) $n, 2, '0', STR_PAD_LEFT) ?></span>
            <span class="tag"><?= e($g['topic']) ?></span>
            <b><?= e($g['short']) ?></b>
            <span><?= e($g['desc']) ?></span>
            <em>Read guide →</em>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
</div>
