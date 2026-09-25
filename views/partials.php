<?php
// Small reusable view helpers.

function stars(float $v): string
{
    $pct = max(0, min(100, $v / 5 * 100));
    return '<span class="stars" style="--p:' . round($pct) . '%" aria-label="Rated ' . number_format($v, 1) . ' out of 5"></span>';
}

function game_card(array $g, bool $lazy = true): string
{
    $load = $lazy ? 'loading="lazy" decoding="async"' : 'fetchpriority="high"';
    $meta = array_filter([version_label($g['latest_version']), size_label($g['latest_size_mb']), $g['is_offline'] ? 'Offline' : '']);
    return '<a class="gcard reveal" href="' . e(game_url($g)) . '">
      <img class="gcard-icon" src="' . e(img($g['icon'], '200x200')) . '" alt="' . e($g['name']) . ' IPA icon" width="100" height="100" ' . $load . '>
      <span class="gcard-body">
        <span class="gcard-name">' . e($g['name']) . '</span>
        <span class="gcard-dev">' . e($g['developer']) . '</span>
        <span class="gcard-meta">' . implode('<i></i>', array_map('e', $meta)) . '</span>
      </span>
      <span class="gcard-rate">' . ($g['rating_value'] > 0 ? '★ ' . number_format((float) $g['rating_value'], 1) : '') . '</span>
    </a>';
}

function game_grid(array $games, bool $lazyFirst = true): string
{
    $h = '<div class="ggrid">';
    foreach ($games as $i => $g) $h .= game_card($g, $lazyFirst || $i > 5);
    return $h . '</div>';
}

function breadcrumbs(array $crumbs): string
{
    $h = '<nav class="crumbs" aria-label="Breadcrumb"><ol>';
    $last = count($crumbs) - 1;
    foreach (array_values($crumbs) as $i => [$name, $href])
        $h .= $i === $last ? '<li aria-current="page">' . e($name) . '</li>' : '<li><a href="' . e($href) . '">' . e($name) . '</a></li>';
    return $h . '</ol></nav>';
}

function faq_block(array $faq, string $heading = 'Frequently asked questions'): string
{
    $h = '<section class="faq reveal"><h2>' . e($heading) . '</h2>';
    foreach ($faq as [$q, $a]) $h .= '<details><summary>' . e($q) . '</summary><div>' . $a . '</div></details>';
    return $h . '</section>';
}

function guide_cards(array $slugs, string $heading = 'Install guides'): string
{
    $h = '<section class="guide-strip reveal"><div class="sec-head"><h2>' . e($heading) . '</h2><a class="see" href="' . url('guides/') . '">All guides →</a></div><div class="gstrip">';
    foreach ($slugs as $s) {
        if (!$g = guide($s)) continue;
        $h .= '<a class="gdcard" href="' . e(guide_url($s)) . '"><span class="tag">' . e($g['topic']) . '</span><b>' . e($g['short']) . '</b><span>' . e(excerpt($g['desc'], 90)) . '</span></a>';
    }
    return $h . '</div></section>';
}

/** Callout boxes used inside guides. */
function note(string $type, string $html): string { return '<aside class="note note-' . e($type) . '">' . $html . '</aside>'; }
