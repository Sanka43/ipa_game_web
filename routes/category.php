<?php
/** @var string $catSlug */
$cat = category($catSlug) ?? not_found();

$sorts = [
    'popular' => ['Most played', 'rating_count DESC'],
    'new'     => ['Newest', 'latest_release_date DESC, id DESC'],
    'rating'  => ['Top rated', 'rating_value DESC, rating_count DESC'],
    'size'    => ['Smallest', 'latest_size_mb ASC'],
];
$sort  = isset($sorts[$_GET['sort'] ?? '']) ? $_GET['sort'] : null;
$order = $sort ? $sorts[$sort][1] : ($cat['order'] ?? 'rating_count DESC');

$per   = cfg('per_page');
$page  = current_page();
$total = games_count($cat['where']);
$pages = (int) ceil($total / $per);
if ($page > max(1, $pages)) not_found();

$games  = games_where($cat['where'], $order, $per, ($page - 1) * $per);
$poster = $games ? (game_screenshots((int) $games[0]['id'], 'ipad', 1)[0] ?? game_screenshots((int) $games[0]['id'], 'iphone', 1)[0] ?? '') : '';
$base   = category_url($catSlug);
$crumbs = [['Home', url()], ['IPA Games', url('ipa-games/')], [$cat['h1'], $base]];

$lower = strtolower($cat['name']);
$faq = [
    ["What are the best $lower IPA games?", $games
        ? 'The most-played right now are ' . implode(', ', array_map(fn($g) => '<a href="' . game_url($g) . '">' . e($g['name']) . '</a>', array_slice($games, 0, 3))) . '. Sort by "Top rated" to rank by score instead.'
        : 'We are still adding games to this category. Check back soon.'],
    ["How do I install $lower IPA games on iPhone?", 'Open a game page and tap Download. Free games open their App Store listing. To sideload an IPA file, follow our <a href="' . guide_url('how-to-install-ipa-on-iphone') . '">IPA install guide</a>.'],
    ["Do these games work on iPad?", 'Nearly all of them do. Each game page says whether it supports iPhone, iPad or both. Only iPad-native games appear in <a href="' . category_url('ipad') . '">IPA games for iPad</a>.'],
];

// Pages with no games are thin content: keep them out of the index until they fill up.
$robots = ($total === 0 || $sort) ? 'noindex, follow' : null;
$canonical = abs_url("ipa-games/$catSlug/" . ($page > 1 ? "?page=$page" : ''));

render('category', compact('cat', 'games', 'poster', 'page', 'pages', 'total', 'base', 'crumbs', 'faq', 'sorts', 'sort'), [
    'title'       => $cat['title'] . ($page > 1 ? " – Page $page" : ''),
    'description' => $cat['desc'],
    'canonical'   => $canonical,
    'robots'      => $robots,
    'og_image'    => $poster ? img($poster, '1200x630') : null,
    'nav'         => in_array($catSlug, ['latest', 'offline'], true) ? $catSlug : 'games',
    'schema'      => array_filter([breadcrumb_ld($crumbs), $total ? faq_ld($faq) : null, $games ? [
        '@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => $cat['h1'],
        'itemListElement' => array_map(fn($g, $i) => ['@type' => 'ListItem', 'position' => ($page - 1) * $per + $i + 1,
            'url' => SITE_URL . substr(game_url($g), strlen(str_replace(' ', '%20', BASE_PATH))), 'name' => $g['name']], $games, array_keys($games)),
    ] : null]),
]);
