<?php
$dlHref = url('dl/ipastore/');
$size   = $file ? $file['size_kb'] . ' KB' : '';
?>
<!-- ═══ APP HERO ═══ -->
<section class="apphero">
  <div class="apphero-glow" aria-hidden="true"></div>
  <div class="wrap apphero-in">
    <div class="apphero-copy">
      <?= breadcrumbs($crumbs) ?>
      <p class="eyebrow reveal"><span class="dot"></span> Free · iPhone &amp; iPad · Installs in seconds</p>
      <h1 class="hero-title reveal"><span class="line">Download</span><span class="line grad">IPAStore</span></h1>
      <p class="hero-sub reveal">The whole IPA Store in your pocket. Browse <?= number_format($total) ?>+ iOS games, get the latest versions first, and open it all from your Home Screen.</p>

      <div class="app-cta reveal">
        <?php if ($file): ?>
          <a class="btn btn-primary btn-lg" href="<?= e($dlHref) ?>" rel="nofollow">Download IPAStore</a>
          <a class="btn btn-ghost btn-lg" href="#install">How to install</a>
          <p class="app-hint" data-ios-hint hidden>📱 Open this page in <strong>Safari on your iPhone or iPad</strong>. The profile only installs on iOS.</p>
        <?php else: ?>
          <p class="dl-soon"><span class="dot"></span> IPAStore app coming soon</p>
          <a class="btn btn-ghost btn-lg" href="<?= url('ipa-games/') ?>">Browse games on the web →</a>
        <?php endif; ?>
      </div>

      <ul class="app-chips reveal">
        <li><b>v<?= e(ltrim($app['version'], 'vV')) ?></b><small>Version</small></li>
        <li><b>iOS <?= e($app['min_ios']) ?>+</b><small>Requires</small></li>
        <li><b><?= $file ? e($size) : '—' ?></b><small>Size</small></li>
        <li><b>Free</b><small>Price</small></li>
      </ul>
    </div>

    <div class="apphero-stage" aria-hidden="true">
      <div class="orbit o1"></div><div class="orbit o2"></div><div class="orbit o3"></div>
      <figure class="app-phone">
        <div class="app-screen">
          <div class="app-bar"><img src="<?= asset('img/logo-128.webp') ?>" alt=""><span>IPA<b>STORE</b></span></div>
          <div class="app-search"></div>
          <div class="app-grid">
            <?php foreach ($icons as $i => $g): ?><img src="<?= e(img($g['icon'], '120x120')) ?>" alt="" loading="<?= $i < 8 ? 'eager' : 'lazy' ?>" style="--i:<?= $i ?>"><?php endforeach; ?>
          </div>
        </div>
      </figure>
      <img class="app-logo-float" src="<?= asset('img/logo-512.png') ?>" alt="" width="220" height="220">
    </div>
  </div>
</section>

<!-- ═══ FEATURES ═══ -->
<section class="sec">
  <div class="wrap">
    <div class="sec-head reveal"><div><p class="kicker">Why IPAStore</p><h2>Everything in one app</h2></div></div>
    <div class="features">
      <?php foreach ($features as $i => [$icon, $title, $text]): ?>
        <div class="feature reveal" style="--d:<?= $i * 60 ?>ms">
          <span class="feature-ic"><?= $icon ?></span>
          <b><?= e($title) ?></b>
          <p><?= e($text) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ INSTALL ═══ -->
<section class="sec academy" id="install">
  <div class="academy-bg" aria-hidden="true"></div>
  <div class="wrap app-install">
    <div class="reveal">
      <p class="kicker">Install</p>
      <h2>Install IPAStore in 4 steps</h2>
      <ol class="steps">
        <li><strong>Open this page in Safari</strong> on your iPhone or iPad and tap <strong>Download IPAStore</strong>. When asked, tap <strong>Allow</strong>.</li>
        <li><strong>Open Settings.</strong> Tap <em>Profile Downloaded</em> at the top. You can also find it in <em>Settings › General › VPN &amp; Device Management</em>.</li>
        <li><strong>Tap Install</strong>, enter your passcode, then tap <strong>Install</strong> again to confirm.</li>
        <li><strong>Done.</strong> The IPAStore icon is on your Home Screen. Tap it to open the store full screen.</li>
      </ol>
      <p class="muted small">To remove it later: <em>Settings › General › VPN &amp; Device Management</em> › IPA Game › <strong>Remove Profile</strong>.</p>
    </div>

    <div class="dl-card reveal">
      <h3 class="h3">Profile information</h3>
      <dl class="dl-info">
        <dt>App</dt><dd>IPAStore</dd>
        <dt>Type</dt><dd>Home Screen web app</dd>
        <dt>Opens</dt><dd><code><?= e($app['opens']) ?></code></dd>
        <dt>Contains</dt><dd>1 Web Clip · no MDM, certificates or VPN</dd>
        <dt>Removable</dt><dd>Yes, at any time</dd>
        <dt>File</dt><dd><code><?= e($file['name'] ?? 'ipastore.mobileconfig') ?></code></dd>
        <dt>Size</dt><dd><?= $file ? e($size) : 'Coming soon' ?></dd>
        <dt>Requires</dt><dd>iOS <?= e($app['min_ios']) ?> or later</dd>
        <dt>Price</dt><dd>Free</dd>
      </dl>
      <?php if ($file): ?><a class="btn btn-primary btn-block" href="<?= e($dlHref) ?>" rel="nofollow">Download IPAStore</a><?php endif; ?>
    </div>
  </div>
</section>

<!-- ═══ FAQ + CTA ═══ -->
<section class="sec">
  <div class="wrap prose-wrap"><?= faq_block($faq, 'IPAStore app — FAQ') ?></div>
</section>

<section class="app-final">
  <div class="wrap reveal">
    <img src="<?= asset('img/logo-128.webp') ?>" alt="" width="88" height="88">
    <h2>Your games. One store.</h2>
    <?php if ($file): ?>
      <a class="btn btn-primary btn-lg" href="<?= e($dlHref) ?>" rel="nofollow">Download IPAStore</a>
    <?php else: ?>
      <a class="btn btn-primary btn-lg" href="<?= url('ipa-games/') ?>">Browse IPA games</a>
    <?php endif; ?>
  </div>
</section>
