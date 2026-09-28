<?php
/** @var string $guideSlug */
$guide = guide($guideSlug) ?? not_found();

// A content file prints the article body and may set: $updated (first published), $faq, $related.
$updated = '2026-09-25';
$faq = $related = [];
ob_start();
require __DIR__ . "/../content/guides/$guideSlug.php";
$body = ob_get_clean();
// Last modified = when the content file last changed (deploys only upload changed files).
$modified = max($updated, date('Y-m-d', filemtime(__DIR__ . "/../content/guides/$guideSlug.php")));

// Give every <h2> an id and build the table of contents from them.
$toc = [];
$body = preg_replace_callback('~<h2>(.*?)</h2>~s', function ($m) use (&$toc) {
    $id = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(strip_tags($m[1]))), '-');
    $toc[] = [$id, strip_tags($m[1])];
    return "<h2 id=\"$id\">{$m[1]}</h2>";
}, $body);
$minutes = max(2, (int) round(str_word_count(strip_tags($body)) / 220));

$related = $related ?: array_slice(array_diff(array_keys(guides()), [$guideSlug]), 0, 3);
$crumbs  = [['Home', url()], ['Guides', url('guides/')], [$guide['short'], guide_url($guideSlug)]];
$picks   = games_where('is_offline=1', 'rating_count DESC', 4);

$schema = [
    ['@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $guide['title'], 'description' => $guide['desc'],
     'dateModified' => $modified, 'datePublished' => $updated, 'mainEntityOfPage' => abs_url("guides/$guideSlug/"),
     'image' => abs_url('assets/img/og-default.jpg'),
     'author' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => abs_url('about/')],
     'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME, 'logo' => ['@type' => 'ImageObject', 'url' => abs_url('assets/img/logo-512.png')]]],
    breadcrumb_ld($crumbs),
];

$updated = $modified;   // the page shows the last update
render('guide', compact('guide', 'body', 'toc', 'minutes', 'updated', 'faq', 'related', 'crumbs', 'picks'), [
    'title'       => $guide['title'],
    'description' => $guide['desc'],
    'canonical'   => abs_url("guides/$guideSlug/"),
    'og_type'     => 'article',
    'nav'         => 'guides',
    'body_class'  => 'is-guide',
    'schema'      => $schema,
]);
