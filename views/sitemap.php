<?php
/** @var array $entries  @var array $crumbs */
$counts = array_count_values(array_column($entries, 2));
?>
<section class="pagehead">
  <div class="pagehead-glow" aria-hidden="true"></div>
  <div class="wrap"><?= breadcrumbs($crumbs) ?><h1>Sitemap</h1>
    <p class="muted"><?= number_format(count($entries)) ?> pages · also available as <a href="<?= url('sitemap.xml') ?>">sitemap.xml</a></p>
  </div>
</section>
<div class="wrap sec sitemap">
  <div class="sm-tools">
    <input type="search" id="sm-q" placeholder="Filter pages…" aria-label="Filter pages" autocomplete="off">
    <div class="chips sm-tabs" role="group" aria-label="Section">
      <button type="button" class="chip on" data-s="">All <span><?= count($entries) ?></span></button>
      <?php foreach ($counts as $s => $n): ?>
        <button type="button" class="chip" data-s="<?= e($s) ?>"><?= e($s) ?> <span><?= $n ?></span></button>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="table-scroll">
    <table class="vtable sm-table">
      <thead><tr><th>#</th><th>Page</th><th>Section</th><th>Last updated</th><th>Priority</th></tr></thead>
      <tbody id="sm-rows">
        <?php foreach ($entries as $i => [$loc, $title, $section, $mod, $pri]): ?>
          <tr data-s="<?= e($section) ?>">
            <td class="sm-n"><?= $i + 1 ?></td>
            <td><a href="<?= e($loc) ?>"><?= e($title) ?></a><small><?= e(substr($loc, strlen(SITE_URL)) ?: '/') ?></small></td>
            <td><span class="pill"><?= e($section) ?></span></td>
            <td class="sm-d"><?= $mod ? e(date_label($mod)) : '—' ?></td>
            <td class="sm-d"><?= e($pri) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="muted" id="sm-empty" hidden>No pages match your filter.</p>
</div>
<script>
(() => {
  const q = document.getElementById('sm-q'), rows = [...document.querySelectorAll('#sm-rows tr')],
        tabs = [...document.querySelectorAll('.sm-tabs button')], empty = document.getElementById('sm-empty');
  let sec = '';
  const apply = () => {
    const t = q.value.trim().toLowerCase();
    let shown = 0;
    for (const r of rows) {
      const ok = (!sec || r.dataset.s === sec) && (!t || r.cells[1].textContent.toLowerCase().includes(t));
      r.hidden = !ok; shown += ok;
    }
    empty.hidden = shown > 0;
  };
  q.addEventListener('input', apply);
  tabs.forEach(b => b.addEventListener('click', () => {
    tabs.forEach(x => x.classList.toggle('on', x === b)); sec = b.dataset.s; apply();
  }));
})();
</script>
