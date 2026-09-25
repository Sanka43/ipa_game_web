<?php
$q     = trim((string) ($_GET['q'] ?? ''));
$page  = current_page();
$per   = cfg('per_page');
[$games, $total] = search_games($q, $per, ($page - 1) * $per);
$pages = (int) ceil($total / $per);

// Guides matching the query too.
$guideHits = $q === '' ? [] : array_filter(guides(), fn($g) => stripos($g['title'] . ' ' . $g['desc'], $q) !== false);

render('search', compact('q', 'games', 'total', 'page', 'pages', 'guideHits'), [
    'title'  => $q !== '' ? "Search: $q" : 'Search IPA games',
    'robots' => 'noindex, follow',
]);
