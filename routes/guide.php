<?php
/** @var string $guideSlug */
$guide = guide($guideSlug) ?? not_found();

// A content file prints the article body and may set: $updated, $howto (steps), $faq, $related.
$updated = '2026-09-25';
$howto = $faq = $related = [];
ob_start();
require __DIR__ . "/../content/guides/$guideSlug.php";
$body = ob_get_clean();

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
     'dateModified' => $updated, 'datePublished' => $updated, 'mainEntityOfPage' => abs_url("guides/$guideSlug/"),
     'author' => ['@type' => 'Organization', 'name' => SITE_NAME], 'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME]],
    breadcrumb_ld($crumbs),
];
if ($howto) $schema[] = ['@context' => 'https://schema.org', '@type' => 'HowTo', 'name' => $guide['title'],
    'step' => array_map(fn($s, $i) => ['@type' => 'HowToStep', 'position' => $i + 1, 'name' => $s[0], 'text' => $s[1]], $howto, array_keys($howto))];
if ($faq) $schema[] = faq_ld($faq);

render('guide', compact('guide', 'body', 'toc', 'minutes', 'updated', 'faq', 'related', 'crumbs', 'picks'), [
    'title'       => $guide['title'],
    'description' => $guide['desc'],
    'canonical'   => abs_url("guides/$guideSlug/"),
    'og_type'     => 'article',
    'nav'         => 'guides',
    'body_class'  => 'is-guide',
    'schema'      => $schema,
]);
