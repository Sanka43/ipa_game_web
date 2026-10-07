<?php /** Standalone page (no site layout, no analytics or ads). */
$renderIssues = function (array $issues) { foreach ($issues as $label => [$sev, $hint, $list]): ?>
    <details<?= $sev === 'high' ? ' open' : '' ?>>
      <summary><span class="pill <?= e($sev) ?>"><?= e($sev === 'high' ? 'Fix' : ($sev === 'warn' ? 'Improve' : 'Info')) ?></span><?= e($label) ?><span class="n"><?= count($list) ?></span></summary>
      <p class="hint"><?= e($hint) ?></p>
      <ul><?php foreach ($list as $r): ?><li><a href="<?= e($r[1]) ?>" target="_blank" rel="noopener"><?= e($r[0]) ?></a><?php if (!empty($r[2])): ?><small><?= e($r[2]) ?></small><?php endif; ?></li><?php endforeach; ?></ul>
    </details>
  <?php endforeach; };
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow"><title>SEO health</title>
<style>
:root{color-scheme:dark light;--bg:#0b0d14;--fg:#e8eaf2;--mut:#8a90a6;--card:#141826;--line:#252b3f;--hi:#ff5c6c;--wa:#f5b942;--in:#3df5ff}
@media(prefers-color-scheme:light){:root{--bg:#f5f6fa;--fg:#161a28;--mut:#5b627a;--card:#fff;--line:#dfe2ec}}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--fg);font:15px/1.5 system-ui,sans-serif}
main{max-width:980px;margin:0 auto;padding:24px 16px 64px}h1{font-size:1.5rem;margin:0 0 4px}a{color:var(--in)}
.mut{color:var(--mut)}.top{display:flex;justify-content:space-between;align-items:baseline;gap:12px;flex-wrap:wrap}
.stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin:20px 0}
.stat{background:var(--card);border:1px solid var(--line);border-radius:10px;padding:12px 14px}.stat b{display:block;font-size:1.5rem}
details{background:var(--card);border:1px solid var(--line);border-radius:10px;margin:10px 0}summary{cursor:pointer;padding:12px 14px;display:flex;gap:10px;align-items:center}
.pill{font-size:.7rem;font-weight:700;text-transform:uppercase;padding:2px 8px;border-radius:99px;color:#111}.high{background:var(--hi)}.warn{background:var(--wa)}.info{background:var(--in)}
.n{margin-left:auto;font-weight:700}.hint{padding:0 14px 8px;margin:0;color:var(--mut)}
ul{list-style:none;margin:0;padding:0 14px 12px;max-height:420px;overflow:auto}li{padding:6px 0;border-top:1px solid var(--line);display:flex;gap:10px;justify-content:space-between;flex-wrap:wrap}
li small{color:var(--mut);word-break:break-word}
.scanform{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:12px 0}h2{font-size:1.15rem;margin:28px 0 8px}select{font:inherit;padding:8px;border-radius:8px;border:1px solid var(--line);background:var(--card);color:var(--fg)}button:disabled{opacity:.6;cursor:wait}
.tabs{display:flex;gap:6px;border-bottom:1px solid var(--line);margin:18px 0 4px;flex-wrap:wrap}.tabs a{padding:10px 16px;border-radius:10px 10px 0 0;text-decoration:none;color:var(--mut);font-weight:600;border:1px solid transparent;border-bottom:0}.tabs a.on{color:var(--fg);background:var(--card);border-color:var(--line);box-shadow:inset 0 -2px 0 var(--in)}
.js .tab{display:none}.js .tab.on{display:block}
form.login{max-width:340px;margin:15vh auto;display:grid;gap:10px}input,button{font:inherit;padding:10px 12px;border-radius:8px;border:1px solid var(--line);background:var(--card);color:var(--fg)}button{cursor:pointer;background:var(--in);color:#111;border:0;font-weight:700}
</style></head><body><main>
<?php if (!$authed): ?>
  <form class="login" method="post" action="<?= e($self) ?>">
    <h1>SEO health</h1>
    <input type="password" name="key" placeholder="Access key" autocomplete="current-password" autofocus required>
    <?php if ($loginError): ?><p class="mut" role="alert">Wrong key.</p><?php endif; ?>
    <button type="submit">Open report</button>
  </form>
<?php else: ?>
  <div class="top"><div><h1>SEO health report</h1><p class="mut">Checked from the database on <?= e(date('M j, Y H:i')) ?> UTC. Fix the red items first.</p></div><a href="<?= e($self) ?>?logout=1">Log out</a></div>
  <nav class="tabs" role="tablist"><a href="#live">Live checks</a><a href="#gsc">Search Console</a><a href="#db">Database checks</a><a href="#downloads">Downloads</a></nav>
  <section id="live" class="tab">
    <h2>Live checks</h2>
    <p class="mut">Requests the sitemap and the pages in it over HTTP, like a crawler: status, redirects, noindex, canonical, title, H1, JSON-LD, alt. Can take up to a minute.</p>
    <form method="post" action="<?= e($self) ?>#live" class="scanform" onsubmit="this.querySelector('button').disabled=true;this.querySelector('button').textContent='Scanning… please wait'">
      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="scan" value="1">
      <label>Game pages to check <select name="sample"><option value="50">50 (random)</option><option value="200">200 (random)</option><option value="500">500 (random)</option></select></label>
      <button type="submit">Run live scan</button>
    </form>
    <?php if ($scan): ?>
      <p class="mut">Last scan: <?= e($scan['at']) ?></p>
      <div class="stats"><?php foreach ($scan['summary'] as $k => $v): ?><div class="stat"><b><?= (int) $v ?></b><span class="mut"><?= e($k) ?></span></div><?php endforeach; ?></div>
      <?php if (!$scan['issues']): ?><p>✔ No problems found in the pages checked.</p><?php endif; ?>
      <?php $renderIssues($scan['issues']); ?>
    <?php endif; ?>
  </section>
  <section id="gsc" class="tab">
    <h2>Search Console</h2>
    <?php if (!$gscReady): ?>
      <p class="mut">Not connected yet. Add a <code>'gsc'</code> entry (<code>key_file</code> and <code>site</code>) to <code>config.live.php</code> and upload the service-account key.</p>
    <?php else: ?>
      <form method="post" action="<?= e($self) ?>#gsc" class="scanform" onsubmit="this.querySelector('button').disabled=true;this.querySelector('button').textContent='Loading…'">
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="gsc" value="1">
        <label>Period <select name="days"><option value="28">Last 28 days</option><option value="7">Last 7 days</option><option value="90">Last 90 days</option></select></label>
        <button type="submit">Load Search Console data</button>
      </form>
      <?php if ($gsc && isset($gsc['error'])): ?>
        <p role="alert"><b>Search Console said:</b> <?= e($gsc['error']) ?></p>
        <p class="mut">Most common cause: the service account email was not added as a user of the property in Search Console, or <code>site</code> does not match the property (<code>sc-domain:ipagame.store</code> for a Domain property, or the full URL with a trailing slash for a URL-prefix property).</p>
      <?php elseif ($gsc):
        $t = $gsc['tot']; $p = $gsc['prev'];
        [$lowCtr, $striking] = gsc_opportunities($gsc['pages']);
        $delta = fn($a, $b) => $b > 0 ? sprintf('%+.0f%% vs previous', ($a - $b) / $b * 100) : 'no previous data';
        $short = fn($u) => rawurldecode(preg_replace('~^https?://[^/]+~', '', $u)) ?: '/';
        $table = function (string $title, string $hint, array $rows, string $dim, string $sev) use ($short) {
            if (!$rows) return; ?>
          <details<?= $sev === 'high' ? ' open' : '' ?>>
            <summary><span class="pill <?= e($sev) ?>"><?= $sev === 'high' ? 'Fix' : ($sev === 'warn' ? 'Improve' : 'Info') ?></span><?= e($title) ?><span class="n"><?= count($rows) ?></span></summary>
            <p class="hint"><?= e($hint) ?></p>
            <ul><?php foreach ($rows as $r): $k = $r['keys'][0]; ?>
              <li><?php if ($dim === 'page'): ?><a href="<?= e($k) ?>" target="_blank" rel="noopener"><?= e($short($k)) ?></a><?php else: ?><span><?= e($k) ?></span><?php endif; ?>
                <small><?= number_format($r['impressions']) ?> impr · <?= number_format($r['clicks']) ?> clicks · <?= number_format($r['ctr'] * 100, 1) ?>% CTR · pos <?= number_format($r['position'], 1) ?></small></li>
            <?php endforeach; ?></ul>
          </details>
        <?php }; ?>
        <p class="mut"><?= e($gsc['from']) ?> to <?= e($gsc['to']) ?> (Google's data is about 3 days behind). Loaded <?= e($gsc['at']) ?>.</p>
        <div class="stats">
          <div class="stat"><b><?= number_format($t['clicks']) ?></b><span class="mut">Clicks · <?= e($delta($t['clicks'], $p['clicks'])) ?></span></div>
          <div class="stat"><b><?= number_format($t['impressions']) ?></b><span class="mut">Impressions · <?= e($delta($t['impressions'], $p['impressions'])) ?></span></div>
          <div class="stat"><b><?= number_format($t['ctr'] * 100, 1) ?>%</b><span class="mut">Average CTR</span></div>
          <div class="stat"><b><?= number_format($t['position'], 1) ?></b><span class="mut">Average position</span></div>
        </div>
        <?php foreach ($gsc['sitemaps'] as $sm): ?>
          <p class="mut">Sitemap <?= e($short($sm['path'])) ?>: <?= number_format($sm['submitted']) ?> URLs submitted<?= $sm['pending'] ? ', still pending' : '' ?>, <?= (int) $sm['errors'] ?> errors, <?= (int) $sm['warnings'] ?> warnings.</p>
        <?php endforeach;
        $table('Seen a lot, clicked rarely', 'Ranking in the top 10 with a CTR under 3%. Improve the title and meta description for these pages.', $lowCtr, 'page', 'high');
        $table('Almost on page one (position 6–20)', 'A small push moves these up: internal links from related games and guides, and richer page content.', $striking, 'page', 'warn');
        $table('Top search queries', 'What people typed before landing on the site.', $gsc['queries'], 'query', 'info');
        $table('Top pages by clicks', 'Pages that bring the most visitors. Keep these fresh.', array_slice($gsc['pages'], 0, 25), 'page', 'info');
      endif; ?>
    <?php endif; ?>
  </section>
  <section id="db" class="tab">
  <h2>Database checks</h2>
  <div class="stats"><?php foreach ($stats as $k => $v): ?><div class="stat"><b><?= (int) $v ?></b><span class="mut"><?= e($k) ?></span></div><?php endforeach; ?></div>
  <?php if (!$issues): ?><p>No issues found.</p><?php endif; ?>
  <?php $renderIssues($issues); ?>
  </section>
  <section id="downloads" class="tab">
    <h2>Downloads</h2>
    <p class="mut">Counted when someone taps the download button. <b>App</b> = IPA Game Store installed after visiting that game's page. <b>IPA file</b> = the game's own IPA downloaded. Bots are ignored. Counting starts from the first download after this feature went live.</p>
    <?php if (!$dl['ready']): ?>
      <p>No downloads recorded yet.</p>
    <?php else: ?>
      <div class="stats">
        <div class="stat"><b><?= number_format($dl['tot']['store'][1]) ?></b><span class="mut">App downloads · last 30 days (<?= number_format($dl['tot']['store'][0]) ?> all time)</span></div>
        <div class="stat"><b><?= number_format($dl['tot']['ipa'][1]) ?></b><span class="mut">IPA file downloads · last 30 days (<?= number_format($dl['tot']['ipa'][0]) ?> all time)</span></div>
      </div>
      <details open>
        <summary><span class="pill info">Top</span>Games that bring the most app downloads<span class="n"><?= count($dl['rows']) ?></span></summary>
        <p class="hint">Ranked by app downloads in the last 30 days. Push the top games with more internal links and fresher content.</p>
        <ul><?php foreach ($dl['rows'] as $r): ?>
          <li><?php if ($r['url']): ?><a href="<?= e($r['url']) ?>" target="_blank" rel="noopener"><?= e($r['name']) ?></a><?php else: ?><span><?= e($r['name']) ?></span><?php endif; ?>
            <small>App: <?= number_format($r['store_30']) ?> (30d) · <?= number_format($r['store_all']) ?> total · IPA file: <?= number_format($r['ipa_30']) ?> (30d) · <?= number_format($r['ipa_all']) ?> total</small></li>
        <?php endforeach; ?></ul>
      </details>
      <details>
        <summary><span class="pill info">Daily</span>Last 14 days</summary>
        <ul><?php foreach ($dl['days'] as $d): ?><li><span><?= e(date('D, M j', strtotime($d['day']))) ?></span><small>App: <?= (int) $d['store'] ?> · IPA file: <?= (int) $d['ipa'] ?></small></li><?php endforeach; ?></ul>
      </details>
    <?php endif; ?>
  </section>
  <script>
  (function(){var ids=['live','gsc','db','downloads'];function show(){var h=location.hash.slice(1);if(ids.indexOf(h)<0)h='live';
    ids.forEach(function(i){document.getElementById(i).classList.toggle('on',i===h)});
    document.querySelectorAll('.tabs a').forEach(function(a){a.classList.toggle('on',a.getAttribute('href')==='#'+h)});}
  document.documentElement.classList.add('js');window.addEventListener('hashchange',show);show();})();
  </script>
<?php endif; ?>
</main></body></html>
