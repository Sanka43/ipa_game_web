<?php
$crumbs = [['Home', url()], ['Guides', url('guides/')]];
$topics = [
    'Install' => 'Install IPA files',
    'Basics'  => 'IPA basics',
    'Safety'  => 'Safety & verification',
    'Fix'     => 'Troubleshooting',
];
$byTopic = [];
foreach (guides() as $s => $g) $byTopic[$g['topic']][$s] = $g;

render('guides', compact('crumbs', 'topics', 'byTopic'), [
    'title'       => 'IPA Guides – Install, Sideload & Fix IPA Files on iPhone',
    'description' => 'Step-by-step IPA guides: install IPA on iPhone and iPad, AltStore and SideStore setup, installing without a computer, verifying IPA files and fixing errors.',
    'canonical'   => abs_url('guides/'),
    'nav'         => 'guides',
    'schema'      => [breadcrumb_ld($crumbs), [
        '@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'IPA Guides',
        'itemListElement' => array_map(fn($s, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => abs_url("guides/$s/")], array_keys(guides()), range(0, count(guides()) - 1)),
    ]],
]);
