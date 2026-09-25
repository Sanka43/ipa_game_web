<?php $dlHref = url("dl/{$g['slug']}/"); ?>
<!-- ═══ DOWNLOAD HERO ═══ -->
<section class="dlhero">
  <?php if ($shots): ?><div class="ghero-bg" aria-hidden="true" style="background-image:url('<?= e(img($shots[0], '1400x0w')) ?>')"></div><?php endif; ?>
  <div class="ghero-shade" aria-hidden="true"></div>
  <div class="wrap">
    <?= breadcrumbs($crumbs) ?>
    <div class="dlhero-in">
      <div class="dl-ring reveal<?= $file ? '' : ' idle' ?>" data-countdown="<?= $file ? 5 : 0 ?>">
        <svg viewBox="0 0 120 120" aria-hidden="true">
          <defs><linearGradient id="dlg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3df5ff"/><stop offset=".5" stop-color="#8b5cff"/><stop offset="1" stop-color="#ff3daa"/></linearGradient></defs>
          <circle class="dl-track" cx="60" cy="60" r="56"/>
          <circle class="dl-prog" cx="60" cy="60" r="56" pathLength="100"/>
        </svg>
        <img src="<?= e(img($g['icon'], '360x360')) ?>" alt="<?= e($name) ?> IPA icon" width="150" height="150" fetchpriority="high">
      </div>

      <p class="kicker reveal"><?= $cat['icon'] ?> <?= e($cat['name']) ?> · <?= e($devs) ?></p>
      <h1 class="reveal">Download <?= e($name) ?> <span class="grad">IPA</span></h1>
      <p class="dl-sub reveal"><?= e(implode(' · ', array_filter([$ver, $size, $ios ? "$ios+" : '']))) ?></p>

      <?php if ($file): ?>
        <div class="dl-action reveal" aria-live="polite">
          <p class="dl-wait" data-wait>Preparing your download… <b data-count>5</b></p>
          <a class="btn btn-primary btn-lg dl-btn" href="<?= e($dlHref) ?>" rel="nofollow" data-dl hidden>⬇ Download IPA · <?= e($size) ?></a>
          <p class="dl-hint" data-dl hidden>Download didn't start? <a href="<?= e($dlHref) ?>" rel="nofollow">Click here</a> · <a href="#install">How to install</a></p>
          <noscript><a class="btn btn-primary btn-lg" href="<?= e($dlHref) ?>" rel="nofollow">⬇ Download IPA · <?= e($size) ?></a></noscript>
        </div>
      <?php else: ?>
        <div class="dl-action reveal">
          <p class="dl-soon"><span class="dot"></span> IPA file coming soon</p>
          <p class="dl-hint">We're preparing the <?= e($name) ?> IPA file. Check back shortly.</p>
          <div class="hero-cta dl-cta">
            <a class="btn btn-ghost" href="<?= e(game_url($g)) ?>">← Back to <?= e(excerpt($name, 28)) ?></a>
            <?php if ($g['app_store_url']): ?><a class="btn btn-ghost" href="<?= url("out/{$g['slug']}/") ?>" rel="nofollow noopener" target="_blank">View on App Store</a><?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="wrap dl-grid">
  <!-- File information -->
  <section class="dl-card reveal">
    <h2 class="h3">File information</h2>
    <dl class="dl-info">
      <dt>File name</dt><dd><code><?= e($file['name'] ?? "{$g['slug']}.ipa") ?></code></dd>
      <dt>Version</dt><dd><?= e($ver ?: '—') ?></dd>
      <dt>File size</dt><dd><?= e($size) ?></dd>
      <dt>Requires</dt><dd><?= e($ios ? "$ios or later" : '—') ?></dd>
      <dt>Compatible</dt><dd><?= e($devs) ?></dd>
      <dt>Developer</dt><dd><?= e($g['developer']) ?></dd>
      <?php if ($g['bundle_id']): ?><dt>Bundle ID</dt><dd><code><?= e($g['bundle_id']) ?></code></dd><?php endif; ?>
      <dt>Updated</dt><dd><?= e(date_label($file['updated'] ?? $g['latest_release_date'])) ?></dd>
    </dl>
    <?php if (!empty($file['sha256'])): ?>
      <div class="sha">
        <span>SHA-256</span>
        <code data-sha><?= e($file['sha256']) ?></code>
        <button class="copy" data-copy="<?= e($file['sha256']) ?>">Copy</button>
      </div>
    <?php endif; ?>
  </section>

  <!-- Install steps -->
  <section class="dl-card reveal" id="install">
    <h2 class="h3">Install in 3 steps</h2>
    <ol class="steps">
      <li><strong>Download</strong> the IPA above and save it to the Files app (or to your computer).</li>
      <li><strong>Sign &amp; install</strong> it with a sideloading tool using your Apple ID.</li>
      <li><strong>Trust &amp; open.</strong> Trust your Apple ID in <em>Settings › General › VPN &amp; Device Management</em> and turn on <em>Developer Mode</em>.</li>
    </ol>
    <div class="methods">
      <a href="<?= guide_url('install-ipa-with-altstore') ?>"><b>AltStore</b><small>PC or Mac · auto refresh</small></a>
      <a href="<?= guide_url('install-ipa-with-sidestore') ?>"><b>SideStore</b><small>No PC after setup</small></a>
      <a href="<?= guide_url('install-ipa-on-ipad') ?>"><b>On iPad</b><small>iPadOS guide</small></a>
    </div>
    <p class="muted small">Stuck? See <a href="<?= guide_url('ipa-installation-failed') ?>">why an IPA installation fails</a>.</p>
  </section>
</div>

<?php if (!empty($file['sha256'])): ?>
<div class="wrap">
  <section class="dl-card dl-verify reveal">
    <h2 class="h3">Verify this file</h2>
    <p class="muted">Before installing, check that your download matches the checksum above. The two values must be identical.</p>
    <div class="table-scroll"><table>
      <tr><th>Windows</th><td><code>Get-FileHash .\<?= e($file['name']) ?> -Algorithm SHA256</code></td></tr>
      <tr><th>Mac</th><td><code>shasum -a 256 <?= e($file['name']) ?></code></td></tr>
    </table></div>
    <p class="muted small">More checks: <a href="<?= guide_url('how-to-verify-ipa-file') ?>">how to verify an IPA file</a>.</p>
  </section>
</div>
<?php endif; ?>

<?php if ($similar): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head"><h2>More <?= e(strtolower($cat['name'])) ?> IPA games</h2><a class="see" href="<?= category_url($g['category']) ?>">See all →</a></div>
    <?= game_grid($similar) ?>
  </div>
</section>
<?php endif; ?>
