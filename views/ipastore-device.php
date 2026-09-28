<section class="gate">
  <div class="apphero-glow" aria-hidden="true"></div>
  <div class="wrap gate-in">
    <?= breadcrumbs($crumbs) ?>

    <div class="gate-card reveal">
      <!-- Left: message, steps, actions -->
      <div class="gate-copy">
        <div class="gate-art" aria-hidden="true">
          <img class="gate-tablet" src="<?= asset('img/gate-ipad.webp') ?>" alt="" width="469" height="360">
          <img class="gate-phone" src="<?= asset('img/gate-iphone.webp') ?>" alt="" width="215" height="440">
        </div>

        <p class="kicker">iPhone &amp; iPad only</p>
        <h1>Open this page on your <span class="grad">iPhone or iPad</span></h1>
        <p class="lead">IPAStore is made for iOS and installs from <strong>Safari on your iPhone or iPad</strong>. Open this page there to continue.</p>

        <ol class="steps gate-steps">
          <li>
            <span class="step-head"><span><strong>Open Safari</strong> on your iPhone or iPad.</span></span>
          </li>
          <li>
            <span class="step-head">
              <strong>Search Google for</strong>
              <span class="gate-search" aria-label="Search: ipagame store">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                ipagame store
              </span>
            </span>
            <small>Tap the IPAStore result, or type <b><?= e($host) ?></b> straight into the address bar.</small>
          </li>
          <li>
            <span class="step-head"><span><strong>Tap Download IPA Game Store</strong> and allow the download.</span></span>
            <small>Open <b>Settings › Profile Downloaded</b> and tap <b>Install</b>. IPAStore appears on your Home Screen.</small>
          </li>
        </ol>

        <div class="gate-actions">
          <a class="btn btn-ghost btn-lg" href="<?= url('download-ipastore/') ?>" data-back>← Back</a>
          <a class="btn btn-primary btn-lg" href="<?= url('ipa-games/') ?>">Browse games</a>
        </div>
      </div>

    </div>
  </div>
</section>
