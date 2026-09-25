<?php
$entries = require __DIR__ . '/sitemap_entries.php';
$crumbs = [['Home', url()], ['Sitemap', url('sitemap/')]];
render('sitemap', compact('entries', 'crumbs'), [
    'title' => 'Sitemap', 'description' => 'Every page on ' . SITE_NAME . ': IPA game categories, guides and all ' . count($entries) . ' game pages in one list.',
    'canonical' => abs_url('sitemap/'), 'schema' => [breadcrumb_ld($crumbs)],
]);
