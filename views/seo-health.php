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
.dl-card{background:var(--card);border:1px solid var(--line);border-radius:10px;padding:14px 16px;margin:10px 0;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.dl-card:hover{border-color:var(--mut)}
.dl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:10px;margin:10px 0}
.dl-top-item{background:var(--bg);border:1px solid var(--line);border-radius:8px;padding:10px 12px;display:flex;align-items:center;gap:10px}
.dl-num-input{width:130px;padding:8px 10px;font-weight:700;font-size:1rem;text-align:right;border-radius:8px;border:1px solid var(--line);background:var(--bg);color:var(--fg)}
</style></head><body><main>
<?php if (!$authed): ?>
  <form class="login" method="post" action="<?= e($self) ?>">
    <h1>SEO health</h1>
    <input type="password" name="key" placeholder="Access key" autocomplete="current-password" autofocus required>
    <?php if (cfg('debug') && (string) cfg('seo_health_key') === ''): ?><p class="mut" style="font-size:.8rem;margin:0">Local dev key: <code>admin</code></p><?php endif; ?>
    <?php if ($loginError): ?><p class="mut" role="alert">Wrong key.</p><?php endif; ?>
    <button type="submit">Open report</button>
  </form>
<?php else: ?>
  <div class="top"><div><h1>SEO health report</h1><p class="mut">Checked from the database on <?= e(date('M j, Y H:i')) ?> UTC. Fix the red items first.</p></div><a href="<?= e($self) ?>?logout=1">Log out</a></div>
  <nav class="tabs" role="tablist"><a href="#live">Live checks</a><a href="#gsc">Search Console</a><a href="#db">Database checks</a><a href="#downloads">Downloads</a><a href="#manage-downloads">Manage Downloads</a><a href="#changes">Change log</a></nav>
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
  <section id="manage-downloads" class="tab">
    <div style="display:flex;justify-content:space-between;align-items:baseline;flex-wrap:wrap;gap:12px;margin:28px 0 8px">
      <div>
        <h2 style="margin:0 0 4px">Manage Game Downloads</h2>
        <p class="mut" style="margin:0">Find any game to view and change its download count. Updates will instantly change rankings in the Home page <b>Top 10 Most Downloaded IPA Games</b> section.</p>
      </div>
    </div>

    <?php if ($dlMsg): ?>
      <p id="dl-status-banner" style="background:#0d3329;color:#3df5b8;border:1px solid #1f6b52;border-radius:8px;padding:12px 16px;margin:16px 0;font-weight:600">✔ <?= e($dlMsg) ?></p>
    <?php endif; ?>

    <!-- Current Top 10 Live Preview -->
    <details open style="margin-bottom:20px">
      <summary>
        <span class="pill high">Live Top 10</span>
        Home Page Current Ranking
        <span class="n"><?= count($currentTop10) ?></span>
      </summary>
      <p class="hint">The 10 games currently appearing on the Home page "Top 10 Most Downloaded IPA Games" chart.</p>
      <div style="padding:0 14px 14px">
        <div class="dl-grid">
          <?php foreach ($currentTop10 as $idx => $tg): ?>
            <div class="dl-top-item">
              <span style="font-size:1.15rem;font-weight:800;color:var(--in);width:24px;text-align:center;flex-shrink:0"><?= $idx + 1 ?></span>
              <?php if (!empty($tg['icon'])): ?>
                <img src="<?= e(img($tg['icon'], '80x80')) ?>" alt="" style="width:38px;height:38px;border-radius:8px;object-fit:cover;background:#222;flex-shrink:0" onerror="this.style.display='none'">
              <?php endif; ?>
              <div style="flex:1;min-width:0">
                <a href="<?= e(game_url($tg)) ?>" target="_blank" rel="noopener" style="font-weight:600;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($tg['name']) ?></a>
                <small class="mut"><?= number_format((int) $tg['dl']) ?> dl · ★ <?= number_format((float) $tg['rating_value'], 1) ?></small>
              </div>
              <button type="button" onclick="selectGameForEdit(<?= (int) $tg['id'] ?>, '<?= e(addslashes($tg['name'])) ?>')" style="padding:4px 10px;font-size:.75rem;cursor:pointer">Edit</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </details>

    <!-- Game Finder / Search -->
    <div style="background:var(--card);border:1px solid var(--line);border-radius:10px;padding:16px;margin:20px 0">
      <h3 style="margin:0 0 10px;font-size:1rem">Find Game</h3>
      <form method="get" action="<?= e($self) ?>" id="dl-search-form" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <input type="hidden" name="tab" value="manage-downloads">
        <input type="text" id="dl-search-input" name="q" placeholder="Type game name, slug, or ID (e.g. Minecraft, Roblox, 13)..." value="<?= e($dlSearchQ) ?>" autocomplete="off" style="flex:1;min-width:240px;padding:10px 14px">
        <button type="submit">Search</button>
        <?php if ($dlSearchQ !== ''): ?>
          <a href="<?= e($self) ?>#manage-downloads" style="color:var(--mut);text-decoration:none;padding:10px 14px;border:1px solid var(--line);border-radius:8px">Reset</a>
        <?php endif; ?>
      </form>
      <p class="mut" style="margin:8px 0 0;font-size:.8rem">Search as you type (live search) or click Search / press Enter.</p>
    </div>

    <!-- Results List -->
    <div id="dl-results-container">
      <h3 style="margin:16px 0 10px;font-size:1rem" id="dl-results-heading">
        <?= $dlSearchQ !== '' ? 'Search Results for "' . e($dlSearchQ) . '" (' . count($manageGames) . ' found)' : 'Top Downloaded Games (' . count($manageGames) . ' shown)' ?>
      </h3>

      <?php if (empty($manageGames)): ?>
        <p class="mut" id="dl-no-games" style="padding:20px;text-align:center;background:var(--card);border:1px solid var(--line);border-radius:10px">No games found matching your search. Try another name or ID.</p>
      <?php else: ?>
        <div id="dl-games-list" style="display:grid;gap:10px">
          <?php foreach ($manageGames as $g):
            $topRankIndex = array_search($g['id'], $top10Ids, true);
            $isTop10 = ($topRankIndex !== false);
          ?>
            <div class="dl-card dl-item" id="game-card-<?= (int) $g['id'] ?>">
              <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:260px">
                <?php if (!empty($g['icon'])): ?>
                  <img src="<?= e(img($g['icon'], '80x80')) ?>" alt="" style="width:48px;height:48px;border-radius:10px;object-fit:cover;background:#222;flex-shrink:0" onerror="this.style.display='none'">
                <?php endif; ?>
                <div>
                  <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <a href="<?= e(game_url($g)) ?>" target="_blank" rel="noopener" style="font-weight:700;font-size:1rem;color:var(--fg);text-decoration:none">
                      <?= e($g['name']) ?>
                    </a>
                    <?php if ($isTop10): ?>
                      <span class="pill high" title="Ranked in Home page Top 10">Top #<?= $topRankIndex + 1 ?></span>
                    <?php endif; ?>
                    <span class="mut" style="font-size:.75rem">ID: <?= (int) $g['id'] ?></span>
                  </div>
                  <div class="mut" style="font-size:.8rem;margin-top:2px">
                    Category: <span style="color:var(--fg)"><?= e(ucfirst($g['category'])) ?></span> ·
                    Ratings: <span style="color:var(--fg)"><?= compact_num($g['rating_count']) ?></span> (★ <?= number_format((float) $g['rating_value'], 1) ?>)
                  </div>
                </div>
              </div>

              <!-- Download update form -->
              <form method="post" action="<?= e($self) ?>#manage-downloads" class="dl-form-async" data-game-id="<?= (int) $g['id'] ?>" style="display:flex;align-items:center;gap:10px;margin:0">
                <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                <input type="hidden" name="set_downloads" value="1">
                <input type="hidden" name="game_id" value="<?= (int) $g['id'] ?>">
                <input type="hidden" name="search_query" value="<?= e($dlSearchQ) ?>">
                <div style="text-align:right">
                  <span class="mut" style="display:block;font-size:.75rem">Downloads Count</span>
                  <input type="number" name="downloads" id="input-dl-<?= (int) $g['id'] ?>" class="dl-num-input" value="<?= (int) $g['downloads'] ?>" min="0" step="1" required>
                </div>
                <button type="submit" style="padding:10px 18px;height:42px;margin-top:14px" class="save-btn">Save</button>
                <span class="save-feedback" id="feedback-<?= (int) $g['id'] ?>" style="display:none;font-weight:bold;color:#3df5b8;font-size:.85rem">✓</span>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <section id="changes" class="tab">
    <h2>Change log</h2>
    <p class="mut">Record every significant SEO change. With Search Console connected, the page's clicks, impressions, CTR and position for the last 28 days are saved as the baseline. Load Search Console data on its tab and the "now" numbers appear here. Wait 2–4 weeks before judging a change.</p>
    <?php if ($changeMsg): ?><p role="alert"><?= e($changeMsg) ?></p><?php endif; ?>
    <form method="post" action="<?= e($self) ?>#changes" class="scanform">
      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="addchange" value="1">
      <input type="date" name="changed_on" value="<?= e(date('Y-m-d')) ?>" required>
      <select name="kind"><?php foreach (CHANGE_KINDS as $k => $l): ?><option value="<?= e($k) ?>"><?= e($l) ?></option><?php endforeach; ?></select>
      <input type="text" name="page" placeholder="/ipa-games/sports/efootball-ipa/ (optional)" size="34">
      <input type="text" name="note" placeholder="What changed" size="40" maxlength="1000" required>
      <button type="submit">Add</button>
    </form>
    <?php
      $nowByPage = [];
      if (!empty($gsc['pages'])) foreach ($gsc['pages'] as $r) $nowByPage[$r['keys'][0]] = $r;
    ?>
    <?php if (!$changes): ?><p>No changes recorded yet.</p><?php else: ?>
    <ul>
      <?php foreach ($changes as $c): $now = $nowByPage[$c['page']] ?? null; ?>
      <li>
        <span><b><?= e(date('M j, Y', strtotime($c['changed_on']))) ?></b> · <?= e(CHANGE_KINDS[$c['kind']] ?? $c['kind']) ?><br><?= e($c['note']) ?>
          <?php if ($c['page']): ?><br><a href="<?= e($c['page']) ?>" target="_blank" rel="noopener"><?= e(rawurldecode(preg_replace('~^https?://[^/]+~', '', $c['page'])) ?: '/') ?></a><?php endif; ?></span>
        <small>
          <?php if ($c['base_impr'] !== null): ?>Before: <?= number_format($c['base_impr']) ?> impr · <?= number_format($c['base_clicks']) ?> clicks · <?= number_format($c['base_ctr'] * 100, 1) ?>% CTR · pos <?= number_format($c['base_pos'], 1) ?><?php else: ?>No baseline<?php endif; ?>
          <?php if ($now): ?><br>Now: <?= number_format($now['impressions']) ?> impr · <?= number_format($now['clicks']) ?> clicks · <?= number_format($now['ctr'] * 100, 1) ?>% CTR · pos <?= number_format($now['position'], 1) ?><?php endif; ?>
        </small>
        <form method="post" action="<?= e($self) ?>#changes" onsubmit="return confirm('Delete this entry?')"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><button type="submit" name="delchange" value="<?= (int) $c['id'] ?>">Delete</button></form>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </section>
  <script>
  (function(){
    var ids = ['live','gsc','db','downloads','manage-downloads','changes'];
    var defaultTab = <?= (!empty($dlSearchQ) || !empty($dlMsg) || ($tab ?? '') === 'manage-downloads') ? "'manage-downloads'" : "'live'" ?>;
    function show(){
      var h = location.hash.slice(1) || defaultTab;
      if (ids.indexOf(h) < 0) h = defaultTab;
      ids.forEach(function(i){
        var el = document.getElementById(i);
        if (el) el.classList.toggle('on', i === h);
      });
      document.querySelectorAll('.tabs a').forEach(function(a){
        a.classList.toggle('on', a.getAttribute('href') === '#' + h);
      });
    }
    document.documentElement.classList.add('js');
    window.addEventListener('hashchange', show);
    show();
  })();

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s || '';
    return d.innerHTML;
  }

  function selectGameForEdit(id, name) {
    var inp = document.getElementById('input-dl-' + id);
    if (inp) {
      inp.scrollIntoView({behavior: 'smooth', block: 'center'});
      inp.focus();
      inp.select();
    } else {
      var search = document.getElementById('dl-search-input');
      if (search) {
        search.value = name;
        document.getElementById('dl-search-form').submit();
      }
    }
  }

  // Live search games via AJAX
  (function(){
    var searchTimer = null;
    var searchInput = document.getElementById('dl-search-input');
    var resultsList = document.getElementById('dl-games-list');
    var resultsHeading = document.getElementById('dl-results-heading');
    var noGamesMsg = document.getElementById('dl-no-games');
    var top10Ids = <?= json_encode($top10Ids) ?>;
    var csrfToken = <?= json_encode($_SESSION['csrf']) ?>;
    var selfUrl = <?= json_encode($self) ?>;

    function bindForms() {
      document.querySelectorAll('.dl-form-async').forEach(function(form) {
        if (form.getAttribute('data-bound')) return;
        form.setAttribute('data-bound', '1');
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          var btn = form.querySelector('button');
          var gid = form.getAttribute('data-game-id');
          var fb = document.getElementById('feedback-' + gid);
          var origText = btn.textContent;
          btn.disabled = true;
          btn.textContent = 'Saving…';

          var fd = new FormData(form);
          fetch(form.action, {
            method: 'POST',
            body: fd,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
          })
          .then(function(r) { return r.json(); })
          .then(function(res) {
            btn.disabled = false;
            btn.textContent = origText;
            if (res.ok) {
              if (fb) {
                fb.textContent = '✓ Saved (' + Number(res.downloads).toLocaleString() + ')';
                fb.style.display = 'inline';
                setTimeout(function() { fb.style.display = 'none'; }, 3000);
              }
            } else {
              alert('Error: ' + (res.error || 'Failed to update'));
            }
          })
          .catch(function() {
            btn.disabled = false;
            btn.textContent = origText;
            form.submit();
          });
        });
      });
    }

    bindForms();

    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        var q = searchInput.value.trim();
        if (q.length < 2) return;

        searchTimer = setTimeout(function() {
          fetch(selfUrl + '?ajax_search=' + encodeURIComponent(q), {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
          })
          .then(function(r){ return r.json(); })
          .then(function(games) {
            if (!resultsHeading) return;
            resultsHeading.textContent = 'Search Results for "' + q + '" (' + games.length + ' found)';
            if (!resultsList) {
              var container = document.getElementById('dl-results-container');
              if (container) {
                resultsList = document.createElement('div');
                resultsList.id = 'dl-games-list';
                resultsList.style.display = 'grid';
                resultsList.style.gap = '10px';
                container.appendChild(resultsList);
              }
            }
            if (!resultsList) return;
            if (noGamesMsg) noGamesMsg.style.display = games.length === 0 ? 'block' : 'none';
            if (games.length === 0) {
              resultsList.innerHTML = '<p class="mut" style="padding:20px;text-align:center;background:var(--card);border:1px solid var(--line);border-radius:10px">No games found matching "' + escapeHtml(q) + '".</p>';
              return;
            }
            var html = '';
            games.forEach(function(g) {
              var gid = parseInt(g.id);
              var topRankIdx = top10Ids.indexOf(gid);
              var topBadge = topRankIdx >= 0 ? '<span class="pill high" title="Ranked in Home page Top 10">Top #' + (topRankIdx + 1) + '</span>' : '';
              var iconHtml = g.icon ? '<img src="' + escapeHtml(g.icon) + '" alt="" style="width:48px;height:48px;border-radius:10px;object-fit:cover;background:#222;flex-shrink:0" onerror="this.style.display=\'none\'">' : '';
              var gamePageUrl = <?= json_encode(url('ipa-games/')) ?> + encodeURIComponent(g.category) + '/' + encodeURIComponent(g.slug) + '-ipa/';

              html += '<div class="dl-card dl-item" id="game-card-' + gid + '">' +
                '<div style="display:flex;align-items:center;gap:14px;flex:1;min-width:260px">' +
                  iconHtml +
                  '<div>' +
                    '<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">' +
                      '<a href="' + gamePageUrl + '" target="_blank" rel="noopener" style="font-weight:700;font-size:1rem;color:var(--fg);text-decoration:none">' + escapeHtml(g.name) + '</a>' +
                      topBadge +
                      '<span class="mut" style="font-size:.75rem">ID: ' + gid + '</span>' +
                    '</div>' +
                    '<div class="mut" style="font-size:.8rem;margin-top:2px">' +
                      'Category: <span style="color:var(--fg)">' + escapeHtml(g.category.charAt(0).toUpperCase() + g.category.slice(1)) + '</span> · ' +
                      'Ratings: <span style="color:var(--fg)">' + Number(g.rating_count || 0).toLocaleString() + '</span> (★ ' + parseFloat(g.rating_value || 0).toFixed(1) + ')' +
                    '</div>' +
                  '</div>' +
                '</div>' +
                '<form method="post" action="' + selfUrl + '#manage-downloads" class="dl-form-async" data-game-id="' + gid + '" style="display:flex;align-items:center;gap:10px;margin:0">' +
                  '<input type="hidden" name="csrf" value="' + csrfToken + '">' +
                  '<input type="hidden" name="set_downloads" value="1">' +
                  '<input type="hidden" name="game_id" value="' + gid + '">' +
                  '<div style="text-align:right">' +
                    '<span class="mut" style="display:block;font-size:.75rem">Downloads Count</span>' +
                    '<input type="number" name="downloads" id="input-dl-' + gid + '" class="dl-num-input" value="' + parseInt(g.downloads || 0) + '" min="0" step="1" required>' +
                  '</div>' +
                  '<button type="submit" style="padding:10px 18px;height:42px;margin-top:14px" class="save-btn">Save</button>' +
                  '<span class="save-feedback" id="feedback-' + gid + '" style="display:none;font-weight:bold;color:#3df5b8;font-size:.85rem">✓</span>' +
                '</form>' +
              '</div>';
            });
            resultsList.innerHTML = html;
            bindForms();
          })
          .catch(function() {});
        }, 250);
      });
    }
  })();
  </script>
<?php endif; ?>
</main></body></html>
