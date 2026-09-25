<?php
// Front controller: every URL that isn't a real file lands here.
require __DIR__ . '/app/bootstrap.php';

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) $path = substr($path, strlen(BASE_PATH));
$path = '/' . trim($path, '/');

// One canonical form per page: trailing slash on directories, none on files.
if ($path !== '/' && !preg_match('/\.(xml|txt)$/', $path) && !str_starts_with($path, '/api/')
    && !str_ends_with(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')) {
    $qs = $_SERVER['QUERY_STRING'] ?? '';
    redirect(url(ltrim($path, '/') . '/') . ($qs ? "?$qs" : ''));
}

$seg = $path === '/' ? [] : explode('/', trim($path, '/'));

try {
    switch ($seg[0] ?? '') {
        case '':
            require __DIR__ . '/routes/home.php';
            break;

        case 'ipa-games':
            if (count($seg) === 1) { require __DIR__ . '/routes/hub.php'; break; }
            if (count($seg) === 2) { $catSlug = $seg[1]; require __DIR__ . '/routes/category.php'; break; }
            if (count($seg) === 3 && str_ends_with($seg[2], '-ipa')) {
                $catSlug = $seg[1];
                $gameSlug = substr($seg[2], 0, -4);
                require __DIR__ . '/routes/game.php';
                break;
            }
            if (count($seg) === 4 && str_ends_with($seg[2], '-ipa') && $seg[3] === 'download') {
                $catSlug = $seg[1];
                $gameSlug = substr($seg[2], 0, -4);
                require __DIR__ . '/routes/download.php';
                break;
            }
            not_found();

        case 'guides':
            if (count($seg) === 1) { require __DIR__ . '/routes/guides.php'; break; }
            if (count($seg) === 2) { $guideSlug = $seg[1]; require __DIR__ . '/routes/guide.php'; break; }
            not_found();

        case 'search':
            require __DIR__ . '/routes/search.php';
            break;

        case 'api':
            if (($seg[1] ?? '') === 'search') { require __DIR__ . '/routes/api-search.php'; break; }
            not_found();

        case 'download-ipastore':
            if (count($seg) === 1) { require __DIR__ . '/routes/ipastore.php'; break; }
            if (count($seg) === 2 && $seg[1] === 'open-on-iphone') { require __DIR__ . '/routes/ipastore-device.php'; break; }
            not_found();

        case 'dl':
            header('X-Robots-Tag: noindex');
            if (($seg[1] ?? '') === 'ipastore') {
                // The profile only installs on iOS. (iPads report a Mac user agent, so Macs are let through.)
                if (preg_match('/Windows|Android|CrOS|Linux(?!.*Android)/i', $_SERVER['HTTP_USER_AGENT'] ?? '')
                    && !preg_match('/iPhone|iPad|iPod/i', $_SERVER['HTTP_USER_AGENT'] ?? '')) {
                    redirect(url('download-ipastore/open-on-iphone/'), 302);
                }
                $file = ipastore_profile();
                redirect($file ? $file['url'] : url('download-ipastore/'), 302);
            }
            // The actual IPA file, behind a download counter. No file yet → back to the download page.
            $g = isset($seg[1]) ? game_by_slug($seg[1]) : null;
            if (!$g) not_found();
            header('X-Robots-Tag: noindex');
            $file = ipa_file($g);
            if (!$file) redirect(game_url($g) . 'download/', 302);
            db()->prepare('UPDATE games SET downloads=downloads+1 WHERE id=?')->execute([$g['id']]);
            redirect($file['url'], 302);

        case 'out':
            // Outbound link to the game's App Store listing.
            $g = isset($seg[1]) ? game_by_slug($seg[1]) : null;
            if (!$g || !$g['app_store_url']) not_found();
            header('X-Robots-Tag: noindex');
            redirect($g['app_store_url'], 302);

        case 'sitemap.xml':
            require __DIR__ . '/routes/sitemap.php';
            break;

        case 'sitemap':
            if (count($seg) > 1) not_found();
            require __DIR__ . '/routes/sitemap_page.php';
            break;

        case 'robots.txt':
            header('Content-Type: text/plain; charset=utf-8');
            echo "User-agent: *\nDisallow: /search/\nDisallow: /out/\nDisallow: /dl/\nDisallow: /downloads/\nDisallow: /api/\n\nSitemap: " . abs_url('sitemap.xml') . "\n";
            break;

        case 'about': case 'dmca': case 'disclaimer': case 'privacy': case 'contact':
            if (count($seg) > 1) not_found();
            $pageSlug = $seg[0];
            require __DIR__ . '/routes/page.php';
            break;

        default:
            not_found();
    }
} catch (PDOException $ex) {
    http_response_code(500);
    echo cfg('debug') ? '<pre>' . e($ex->getMessage()) . '</pre>' : 'Service temporarily unavailable.';
}
