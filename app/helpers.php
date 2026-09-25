<?php

function e(?string $s): string { return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/** Site-relative URL: url('ipa-games/racing/') → /base/ipa-games/racing/ */
function url(string $path = ''): string { return str_replace(' ', '%20', BASE_PATH) . '/' . ltrim($path, '/'); }
function abs_url(string $path = ''): string { return SITE_URL . '/' . ltrim($path, '/'); }
function asset(string $path): string
{
    $file = __DIR__ . '/../assets/' . $path;
    return url('assets/' . $path) . (is_file($file) ? '?v=' . filemtime($file) : '');
}

function game_url(array $g): string     { return url("ipa-games/{$g['category']}/{$g['slug']}-ipa/"); }
function category_url(string $c): string { return url("ipa-games/$c/"); }
function guide_url(string $s): string    { return url("guides/$s/"); }

/** App Store CDN images accept any size in the file name: .../512x512bb.jpg */
function img(string $u, string $size): string
{
    // "600x0w" = fixed width; "200x200" = fit inside a box (needs the "bb" suffix).
    $suffix = str_ends_with($size, 'w') ? '' : 'bb';
    return preg_replace('~/\d+x\d+bb\.(jpg|png|webp)$~i', "/{$size}{$suffix}.webp", $u) ?: $u;
}

function version_label(?string $v): string { $v = trim((string) $v); return $v === '' ? '' : 'v' . ltrim($v, 'vV'); }
function size_label($mb): string { $mb = (float) $mb; return $mb >= 1024 ? round($mb / 1024, 2) . ' GB' : ($mb > 0 ? round($mb, 1) . ' MB' : '—'); }
function date_label(?string $d): string { return $d ? date('M j, Y', strtotime($d)) : '—'; }
function compact_num($n): string
{
    $n = (int) $n;
    if ($n >= 1e6) return round($n / 1e6, 1) . 'M';
    if ($n >= 1e3) return round($n / 1e3, 1) . 'K';
    return (string) $n;
}
function excerpt(?string $s, int $len): string
{
    $s = trim(preg_replace('/\s+/', ' ', (string) $s));
    return mb_strlen($s) > $len ? rtrim(mb_substr($s, 0, $len - 1), " ,.;:-") . '…' : $s;
}
/** Plain text with line breaks → paragraphs and bullet lists. */
function text_to_html(?string $s): string
{
    $out = '';
    foreach (preg_split("/\n\s*\n/", trim((string) $s)) as $block) {
        $lines = array_filter(array_map('trim', explode("\n", $block)), 'strlen');
        if (!$lines) continue;
        $bullets = array_filter($lines, fn($l) => preg_match('/^[-•*●]\s*/u', $l));
        if (count($bullets) >= 2 && count($bullets) >= count($lines) - 1) {
            $head = preg_match('/^[-•*●]/u', reset($lines)) ? '' : '<p><strong>' . e(array_shift($lines)) . '</strong></p>';
            $out .= $head . '<ul>' . implode('', array_map(fn($l) => '<li>' . e(preg_replace('/^[-•*●]\s*/u', '', $l)) . '</li>', $lines)) . '</ul>';
        } else {
            $out .= '<p>' . implode('<br>', array_map('e', $lines)) . '</p>';
        }
    }
    return $out;
}

function json_ld(array $data): string
{
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>';
}

function breadcrumb_ld(array $crumbs): array
{
    $items = [];
    foreach (array_values($crumbs) as $i => [$name, $href])
        $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => SITE_URL . substr($href, strlen(str_replace(' ', '%20', BASE_PATH)))];
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

function faq_ld(array $faq): array
{
    return ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(fn($q) => [
        '@type' => 'Question', 'name' => $q[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($q[1])],
    ], $faq)];
}

/** Render a view inside the layout. $meta: title, description, canonical, robots, og_image, schema[] */
function render(string $view, array $vars = [], array $meta = []): void
{
    extract($vars);
    ob_start();
    require __DIR__ . "/../views/$view.php";
    $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
}

function not_found(): void
{
    http_response_code(404);
    render('404', [], ['title' => 'Page not found', 'robots' => 'noindex']);
    exit;
}

function redirect(string $to, int $code = 301): void { header("Location: $to", true, $code); exit; }

function current_page(): int { return max(1, (int) ($_GET['page'] ?? 1)); }

function pager(int $page, int $pages, string $base): string
{
    if ($pages <= 1) return '';
    $sep = str_contains($base, '?') ? '&' : '?';
    $href = fn($p) => $p === 1 ? $base : $base . $sep . 'page=' . $p;
    $h = '<nav class="pager" aria-label="Pagination">';
    if ($page > 1) $h .= '<a rel="prev" href="' . e($href($page - 1)) . '">← Prev</a>';
    $win = array_unique(array_filter([1, $page - 2, $page - 1, $page, $page + 1, $page + 2, $pages], fn($p) => $p >= 1 && $p <= $pages));
    sort($win);
    $last = 0;
    foreach ($win as $p) {
        if ($p - $last > 1) $h .= '<span class="gap">…</span>';
        $h .= $p === $page ? '<span aria-current="page">' . $p . '</span>' : '<a href="' . e($href($p)) . '">' . $p . '</a>';
        $last = $p;
    }
    if ($page < $pages) $h .= '<a rel="next" href="' . e($href($page + 1)) . '">Next →</a>';
    return $h . '</nav>';
}
