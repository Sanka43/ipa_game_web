<?php /** Standalone page (no site layout, no analytics or ads). */ ?><!doctype html>
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
  <div class="stats"><?php foreach ($stats as $k => $v): ?><div class="stat"><b><?= (int) $v ?></b><span class="mut"><?= e($k) ?></span></div><?php endforeach; ?></div>
  <?php if (!$issues): ?><p>No issues found.</p><?php endif; ?>
  <?php foreach ($issues as $label => [$sev, $hint, $list]): ?>
    <details<?= $sev === 'high' ? ' open' : '' ?>>
      <summary><span class="pill <?= e($sev) ?>"><?= e($sev === 'high' ? 'Fix' : ($sev === 'warn' ? 'Improve' : 'Info')) ?></span><?= e($label) ?><span class="n"><?= count($list) ?></span></summary>
      <p class="hint"><?= e($hint) ?></p>
      <ul><?php foreach ($list as $r): ?><li><a href="<?= e($r[1]) ?>" target="_blank" rel="noopener"><?= e($r[0]) ?></a><?php if (!empty($r[2])): ?><small><?= e($r[2]) ?></small><?php endif; ?></li><?php endforeach; ?></ul>
    </details>
  <?php endforeach; ?>
<?php endif; ?>
</main></body></html>
