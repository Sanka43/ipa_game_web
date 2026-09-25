<section class="gate">
  <div class="apphero-glow" aria-hidden="true"></div>
  <div class="wrap gate-in">
    <?= breadcrumbs($crumbs) ?>

    <div class="gate-card reveal">
      <div class="gate-art" aria-hidden="true">
        <span class="gate-phone"><img src="<?= asset('img/logo-128.webp') ?>" alt=""></span>
        <span class="gate-tablet"></span>
      </div>

      <p class="kicker">iPhone &amp; iPad only</p>
      <h1>Open this page on your <span class="grad">iPhone or iPad</span></h1>
      <p class="lead">IPAStore installs from <strong>Safari on iOS</strong>. It can't be installed on a computer or an Android phone. Scan the code with your iPhone camera to continue there.</p>

      <div class="gate-qr">
        <div class="qr-box" data-qr="<?= e($pageUrl) ?>" aria-label="QR code for <?= e($pageUrl) ?>" role="img"></div>
        <ol class="steps">
          <li><strong>Open the Camera</strong> on your iPhone or iPad and point it at the code.</li>
          <li><strong>Tap the link</strong> that appears. It opens in Safari.</li>
          <li><strong>Tap Download IPAStore</strong> and follow the install steps.</li>
        </ol>
      </div>

      <div class="gate-link">
        <code><?= e($pageUrl) ?></code>
        <button class="copy" data-copy="<?= e($pageUrl) ?>">Copy</button>
      </div>

      <div class="gate-actions">
        <a class="btn btn-ghost btn-lg" href="<?= url('download-ipastore/') ?>" data-back>← Back</a>
        <a class="btn btn-primary btn-lg" href="<?= url('ipa-games/') ?>">Browse games</a>
      </div>
    </div>
  </div>
</section>
