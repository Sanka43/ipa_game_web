<?php
$backdrop = $ipadShots[0] ?? $shots[0] ?? $g['icon'];
$ext      = $getHref === $storeHref ? ' rel="nofollow noopener" target="_blank"' : '';
$langs    = array_filter(explode(',', $g['languages']));
?>
<!-- ═══ GAME HERO ═══ -->
<section class="ghero">
  <div class="ghero-bg" aria-hidden="true" style="background-image:url('<?= e(img($backdrop, '1400x0w')) ?>')"></div>
  <div class="ghero-shade" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <div class="ghero-in">
      <img class="ghero-icon reveal" src="<?= e(img($g['icon'], '360x360')) ?>" alt="<?= e($name) ?> IPA icon" width="180" height="180" fetchpriority="high">
      <div class="ghero-copy">
        <p class="kicker reveal"><a href="<?= category_url($g['category']) ?>"><?= $cat['icon'] ?> <?= e($cat['name']) ?> game</a><?= $g['is_offline'] ? ' · ✈ Offline' : '' ?></p>
        <h1 class="reveal"><?= e($name) ?><?php if ($isIpa): ?> <span class="grad">IPA</span><?php endif; ?></h1>
        <p class="ghero-dev reveal">by <strong><?= e($g['developer']) ?></strong></p>
        <ul class="ghero-stats reveal">
          <?php if ($g['rating_count'] > 0): ?><li><b><?= number_format((float) $g['rating_value'], 1) ?> <?= stars((float) $g['rating_value']) ?></b><small><?= compact_num($g['rating_count']) ?> ratings</small></li><?php endif; ?>
          <li><b><?= e($ver ?: '—') ?></b><small>Version</small></li>
          <li><b><?= e($size) ?></b><small>Size</small></li>
          <li><b><?= e($ios ? "$ios+" : '—') ?></b><small>Requires</small></li>
          <?php if ($g['content_rating']): ?><li><b><?= e($g['content_rating']) ?></b><small>Age</small></li><?php endif; ?>
        </ul>
        <div class="ghero-cta reveal">
          <a class="btn btn-primary btn-lg btn-get" href="<?= e($getHref) ?>"<?= $ext ?>><?= e($getLabel) ?></a>
          <?php if ($storeHref && $getHref !== $storeHref): ?><a class="btn btn-ghost btn-store" href="<?= e($storeHref) ?>" rel="nofollow noopener" target="_blank">View on App Store</a><?php endif; ?>
          <a class="btn btn-ghost" href="#install">How to install</a>
        </div>
        <p class="ghero-note reveal"><?= $free ? 'Free' : 'Paid' ?> · <?= e($size) ?><?= $hasFile ? ' · SHA-256 checksum on the download page · <a href="' . guide_url('how-to-verify-ipa-file') . '">How to verify</a>' : ($ios ? ' · ' . e($ios) . ' or later' : '') ?></p>
      </div>
    </div>
  </div>
</section>

<div class="wrap game-layout">
  <div class="game-main">

    <!-- Screenshots -->
    <?php if ($shots || $ipadShots): ?>
    <section class="gsec reveal" id="screenshots">
      <div class="sec-head"><h2><?= e($name) ?> screenshots</h2></div>
      <div class="shots-frame">
      <button type="button" class="shots-nav prev" data-dir="-1" aria-label="Previous screenshots" hidden><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg></button>
      <button type="button" class="shots-nav next" data-dir="1" aria-label="Next screenshots" hidden><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></button>
        <div class="shots" data-drag>
          <?php foreach (['iphone' => $shots, 'ipad' => $ipadShots] as $dev => $list): foreach ($list as $i => $s): ?>
            <a href="<?= e(img($s, '1200x0w')) ?>" data-lightbox="shots"><img src="<?= e(img($s, $dev === 'ipad' ? '600x0w' : '460x0w')) ?>" alt="<?= e($name) ?> for <?= $dev === 'ipad' ? 'iPad' : 'iPhone' ?> screenshot <?= $i + 1 ?>" loading="lazy"></a>
          <?php endforeach; endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Description -->
    <section class="gsec reveal" id="about">
      <h2><?= e($name) ?> for iPhone &amp; iPad</h2>
      <div class="prose clamp" data-clamp>
        <?= text_to_html($g['description'] ?: $g['short_description'] ?: "$name is a " . strtolower($cat['name']) . " game by {$g['developer']}.") ?>
      </div>
      <button class="more" data-more hidden>Read more</button>
    </section>

    <!-- What's new -->
    <?php if ($latest): ?>
    <section class="gsec reveal" id="whats-new">
      <div class="sec-head"><h2>What's new in <?= e($name) ?> <?= e($ver) ?></h2><small class="muted"><?= e(date_label($latest['release_date'])) ?></small></div>
      <div class="prose"><?= $latest['changelog'] ? text_to_html($latest['changelog']) : '<p>Bug fixes and performance improvements.</p>' ?></div>
    </section>
    <?php endif; ?>

    <!-- Installation method -->
    <section class="gsec reveal" id="install">
      <h2>How to install <?= e($name) ?> on iPhone &amp; iPad</h2>
      <?php $m = 0; if ($hasFile): ?>
      <h3>Method <?= ++$m ?>: Download the IPA from IPA Game Store</h3>
      <ol class="steps">
        <li>Tap <strong>Get on IPA Game Store</strong> above to open the <?= e($name) ?> download page, then download the <?= e($size) ?> IPA file.</li>
        <li>Install a signing tool: <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> (needs a computer) or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a> (installs on the phone after a one-time setup).</li>
        <li>Save the <code>.ipa</code> file to the Files app, then open it with your signing tool.</li>
        <li>On iOS 16 or later, turn on <em>Settings › Privacy &amp; Security › Developer Mode</em> and restart.</li>
        <li>Open the game. Apps signed with a free Apple ID have to be refreshed every 7 days.</li>
      </ol>
      <?php elseif ($free): ?>
      <h3>Method <?= ++$m ?>: With the IPA Game Store app</h3>
      <ol class="steps">
        <li>Tap <strong>Get on IPA Game Store</strong> above, then <a href="<?= url('download-ipastore/?from=' . $g['slug']) ?>">download IPA Game Store</a> in Safari on your iPhone or iPad.</li>
        <li>Open <em>Settings › Profile Downloaded</em> and tap <em>Install</em>. The app appears on your Home Screen.</li>
        <li>Open IPA Game Store and find <?= e($name) ?>.</li>
      </ol>
      <?php endif; ?>
      <?php if ($storeHref): ?>
        <h3>Method <?= ++$m ?>: From the App Store</h3>
        <p><?= e($name) ?> is <?= $m > 1 ? 'also ' : '' ?>on the App Store<?= $free ? '' : ' as a paid game' ?>. Installing it there means no signing and no 7-day refresh, and updates arrive automatically. <a href="<?= e($storeHref) ?>" rel="nofollow noopener" target="_blank">View <?= e($name) ?> on the App Store</a>.</p>
      <?php endif; ?>
      <?php if ($free && !$hasFile): ?>
        <h3>Method <?= ++$m ?>: Sideload an IPA you own</h3>
        <p>If you have your own copy of the IPA file, sign and install it with <a href="<?= guide_url('install-ipa-with-altstore') ?>">AltStore</a> or <a href="<?= guide_url('install-ipa-with-sidestore') ?>">SideStore</a>. Our <a href="<?= guide_url('how-to-install-ipa-on-iphone') ?>">IPA install guide</a> compares every method.</p>
      <?php endif; ?>
      <p class="muted small">Having trouble? See <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a> or <a href="<?= guide_url('install-ipa-on-ipad') ?>">installing IPA on iPad</a>.</p>
      <div id="container-911b8b732262ab1a6da784c794f8780e"></div>
    </section>

    <!-- Safety -->
    <section class="gsec reveal" id="safety">
      <h2>Is <?= e($name) ?> safe?</h2>
      <ul class="checks">
        <?php if ($hasFile): ?><li class="ok"><b>Checksum published.</b> The download page lists the file's SHA-256, so you can <a href="<?= guide_url('how-to-verify-ipa-file') ?>">confirm your copy is the untouched file</a>.</li><?php else: ?><li class="ok"><b>Check before you install.</b> Only install IPA files from sources you trust, and <a href="<?= guide_url('how-to-verify-ipa-file') ?>">verify the checksum and signature</a> first.</li><?php endif; ?>
        <li class="ok"><b>Developer:</b> <?= e($g['developer']) ?><?= $g['bundle_id'] ? ' · Bundle ID <code>' . e($g['bundle_id']) . '</code>' : '' ?></li>
        <li class="ok"><b>Sandboxed.</b> iOS runs every app in its own sandbox. A game cannot read your other apps' data without asking you.</li>
        <li class="<?= $g['content_rating'] && (int) $g['content_rating'] >= 12 ? 'warn' : 'ok' ?>"><b>Age rating <?= e($g['content_rating'] ?: 'n/a') ?>.</b> Use Screen Time to limit in-app purchases on a child's device.</li>
        <li class="info"><b>Avoid "modded" copies.</b> Modified IPAs that promise unlimited coins can include tracking code and can get your account banned. <a href="<?= guide_url('are-ipa-files-safe') ?>">Read more about IPA safety</a>.</li>
      </ul>
    </section>

    <!-- Update history -->
    <?php if ($versions): ?>
    <section class="gsec reveal" id="versions">
      <h2><?= e($name) ?> update history</h2>
      <div class="table-scroll">
        <table class="vtable">
          <thead><tr><th>Version</th><th>Released</th><th>Size</th><th>Changes</th></tr></thead>
          <tbody>
            <?php foreach ($versions as $i => $v): ?>
              <tr<?= $i === 0 ? ' class="current"' : '' ?>>
                <td><b><?= e(version_label($v['version'])) ?></b><?= $i === 0 ? ' <span class="pill">Latest</span>' : '' ?></td>
                <td><?= e(date_label($v['release_date'])) ?></td>
                <td><?= e(size_label($v['size_mb'])) ?></td>
                <td class="small"><?= e(excerpt($v['changelog'] ?: 'Bug fixes and improvements.', 140)) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?= faq_block($faq, "$name IPA — FAQ") ?>
  </div>

  <!-- Sidebar -->
  <aside class="game-side">
    <div class="infocard reveal">
      <h2 class="h3">Information</h2>
      <dl>
        <dt>Game</dt><dd><?= e($name) ?></dd>
        <dt>Version</dt><dd><?= e($ver ?: '—') ?></dd>
        <dt>File size</dt><dd><?= e($size) ?></dd>
        <dt>Requires</dt><dd><?= e($ios ? "$ios or later" : '—') ?></dd>
        <dt>Compatible</dt><dd><?= e($devs) ?></dd>
        <dt>Developer</dt><dd><?= e($g['developer']) ?></dd>
        <dt>Category</dt><dd><a href="<?= category_url($g['category']) ?>"><?= e($cat['name']) ?></a></dd>
        <dt>Price</dt><dd><?= $free ? 'Free' : '$' . e($g['price']) ?></dd>
        <dt>Offline</dt><dd><?= $g['is_offline'] ? 'Yes' : 'Online required' ?></dd>
        <dt>Updated</dt><dd><?= e(date_label($g['latest_release_date'])) ?></dd>
        <?php if ($langs): ?><dt>Languages</dt><dd><?= e(strtoupper(implode(', ', array_slice($langs, 0, 8)))) ?><?= count($langs) > 8 ? ' +' . (count($langs) - 8) : '' ?></dd><?php endif; ?>
      </dl>
      <a class="btn btn-primary btn-block" href="<?= e($getHref) ?>"<?= $ext ?>><?= e($getLabel) ?></a>
      <?php if ($storeHref && $getHref !== $storeHref): ?><a class="btn btn-ghost btn-block" href="<?= url("out/{$g['slug']}/") ?>" rel="nofollow noopener" target="_blank">View on App Store</a><?php endif; ?>
    </div>
    <nav class="toc reveal" aria-label="On this page">
      <p class="h3">On this page</p>
      <?php if ($shots || $ipadShots): ?><a href="#screenshots">Screenshots</a><?php endif; ?><a href="#about">About</a><?php if ($latest): ?><a href="#whats-new">What's new</a><?php endif; ?><a href="#install">Install</a><a href="#safety">Safety</a><?php if ($versions): ?><a href="#versions">Update history</a><?php endif; ?>
    </nav>
  </aside>
</div>

<?php if ($similar): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>More <?= e(strtolower($cat['name'])) ?> IPA games</h2><a class="see" href="<?= category_url($g['category']) ?>">See all →</a></div>
    <?= game_grid($similar) ?>
  </div>
</section>
<?php endif; ?>

<?php if ($guideSlugs): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>Related IPA guides</h2><a class="see" href="<?= url('guides/') ?>">All IPA guides →</a></div>
    <ul class="guide-links">
      <?php foreach ($guideSlugs as $s): $gd = guide($s); ?>
        <li><a href="<?= guide_url($s) ?>"><span class="tag"><?= e($gd['topic']) ?></span><b><?= e($gd['short']) ?></b></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<!-- Mobile sticky download -->
<div class="dlbar" id="dlbar">
  <img src="<?= e(img($g['icon'], '96x96')) ?>" alt="" width="40" height="40">
  <span><b><?= e($name) ?></b><small><?= e($ver) ?> · <?= e($size) ?></small></span>
  <a class="btn btn-primary" href="<?= e($getHref) ?>"<?= $ext ?>>Get</a>
</div>
