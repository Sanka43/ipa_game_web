<?php
// Data access. The games table is shared with the ipagame.store app (which also stores apps),
// so the public queries are limited to published items; games and apps are told apart by `type`.

const CARD_COLS = 'id, slug, type, name, category, developer, icon, short_description, latest_version, latest_size_mb,
                   latest_release_date, rating_value, rating_count, is_offline, min_ios';

function games_where(string $where, string $order = 'rating_count DESC', int $limit = 12, int $offset = 0, string $type = 'game'): array
{
    $type = db()->quote($type === 'app' ? 'app' : 'game');
    $sql = 'SELECT ' . CARD_COLS . " FROM games WHERE status='published' AND type=$type AND ($where) ORDER BY $order LIMIT $limit OFFSET $offset";
    return db()->query($sql)->fetchAll();
}

function games_count(string $where, string $type = 'game'): int
{
    $type = db()->quote($type === 'app' ? 'app' : 'game');
    return (int) db()->query("SELECT COUNT(*) FROM games WHERE status='published' AND type=$type AND ($where)")->fetchColumn();
}

function game_by_slug(string $slug): ?array
{
    $st = db()->prepare("SELECT * FROM games WHERE slug=? AND status='published'");
    $st->execute([$slug]);
    return $st->fetch() ?: null;
}

function game_screenshots(int $id, string $device = 'iphone', int $limit = 10): array
{
    $st = db()->prepare("SELECT url FROM game_screenshots WHERE game_id=? AND device=? ORDER BY sort LIMIT $limit");
    $st->execute([$id, $device]);
    return $st->fetchAll(PDO::FETCH_COLUMN);
}

function game_versions(int $id): array
{
    $st = db()->prepare('SELECT * FROM game_versions WHERE game_id=? ORDER BY release_date DESC, id DESC');
    $st->execute([$id]);
    return $st->fetchAll();
}

function similar_games(array $g, int $limit = 8): array
{
    $st = db()->prepare('SELECT ' . CARD_COLS . " FROM games WHERE status='published' AND type=? AND category=? AND id<>? ORDER BY rating_count DESC LIMIT $limit");
    $st->execute([($g['type'] ?? 'game') === 'app' ? 'app' : 'game', $g['category'], $g['id']]);
    return $st->fetchAll();
}

/** Hero games with one backdrop screenshot each. */
function hero_games(int $limit = 5): array
{
    $rows = games_where('is_hero=1', 'rating_count DESC', $limit);
    foreach ($rows as &$r) $r['shots'] = game_screenshots((int) $r['id'], 'ipad', 3) ?: game_screenshots((int) $r['id'], 'iphone', 3);
    return $rows;
}

/** First iPhone screenshot per game id, in one query: [id => url]. */
function first_screenshots(array $ids): array
{
    $ids = array_map('intval', $ids);
    if (!$ids) return [];
    return db()->query("SELECT game_id, url FROM game_screenshots WHERE device='iphone' AND sort=0 AND game_id IN (" . implode(',', $ids) . ')')
        ->fetchAll(PDO::FETCH_KEY_PAIR);
}

/** One poster image (first screenshot of the top game) per genre, in one query. */
function genre_posters(array $slugs, string $type = 'game'): array
{
    $out = array_fill_keys($slugs, '');
    if (!$slugs) return $out;
    $in = implode(',', array_map(fn($s) => db()->quote($s), $slugs));
    $rows = db()->query("SELECT g.category, s.url FROM games g JOIN game_screenshots s ON s.game_id=g.id AND s.sort=0 AND s.device='iphone'
                         WHERE g.status='published' AND g.type=" . db()->quote($type === 'app' ? 'app' : 'game') . " AND g.category IN ($in) ORDER BY g.rating_count DESC")->fetchAll();
    foreach ($rows as $r) if ($out[$r['category']] === '') $out[$r['category']] = $r['url'];
    return $out;
}

function genre_counts(string $type = 'game'): array
{
    return db()->query("SELECT category, COUNT(*) n FROM games WHERE status='published' AND type=" . db()->quote($type === 'app' ? 'app' : 'game') . ' GROUP BY category')->fetchAll(PDO::FETCH_KEY_PAIR);
}

function search_games(string $q, int $limit = 24, int $offset = 0): array
{
    $q = trim($q);
    if (mb_strlen($q) < 2) return [[], 0];
    $like = '%' . addcslashes($q, '%_\\') . '%';
    $where = "status='published' AND (name LIKE ? OR developer LIKE ?)";   // games and apps
    $st = db()->prepare("SELECT COUNT(*) FROM games WHERE $where");
    $st->execute([$like, $like]);
    $total = (int) $st->fetchColumn();
    $st = db()->prepare('SELECT ' . CARD_COLS . " FROM games WHERE $where ORDER BY (name LIKE ?) DESC, rating_count DESC LIMIT $limit OFFSET $offset");
    $st->execute([$like, $like, addcslashes($q, '%_\\') . '%']);
    return [$st->fetchAll(), $total];
}

/**
 * The IPA file behind a game's download page, or null when none is available yet.
 * Source order: games.ipa_url (external link) → downloads/{slug}.ipa (uploaded to the site).
 * For local files the SHA-256 is computed once and cached next to the file as {slug}.ipa.sha256.
 */
function ipa_file(array $g): ?array
{
    $name = "{$g['slug']}-" . preg_replace('/[^0-9A-Za-z.]+/', '', ltrim((string) $g['latest_version'], 'vV')) . '.ipa';
    if (!empty($g['ipa_url'])) {
        return ['url' => $g['ipa_url'], 'name' => basename(parse_url($g['ipa_url'], PHP_URL_PATH)) ?: $name,
                'size_mb' => (float) $g['latest_size_mb'], 'sha256' => $g['ipa_sha256'] ?: null, 'updated' => $g['latest_release_date']];
    }
    $path = __DIR__ . "/../downloads/{$g['slug']}.ipa";
    if (!is_file($path)) return null;

    $cache = "$path.sha256";
    if (!is_file($cache) || filemtime($cache) < filemtime($path)) @file_put_contents($cache, hash_file('sha256', $path));
    return ['url' => url("downloads/{$g['slug']}.ipa"), 'name' => $name, 'size_mb' => filesize($path) / 1048576,
            'sha256' => trim((string) @file_get_contents($cache)) ?: null, 'updated' => date('Y-m-d', filemtime($path))];
}

/** The IPAStore Web Clip profile offered on /download-ipastore/, or null if the file is missing. */
function ipastore_profile(): ?array
{
    $app   = cfg('ipastore_app');
    $path  = __DIR__ . '/../' . $app['file'];
    $tmpl  = __DIR__ . '/../downloads/ipastore-template.mobileconfig';
    $check = is_file($path) ? $path : (is_file($tmpl) ? $tmpl : null);
    if (!$check) return null;
    return ['url' => url('dl/ipastore/'), 'name' => basename($path), 'size_kb' => (int) ceil(filesize($check) / 1024),
            'updated' => date('Y-m-d', filemtime($check))];
}

/** Check if a file starts with XML / plist headers (i.e. unsigned). */
function is_file_xml(string $path): bool
{
    $fp = @fopen($path, 'rb');
    if (!$fp) return false;
    $head = fread($fp, 64);
    fclose($fp);
    return str_starts_with(ltrim($head), '<?xml') || str_starts_with(ltrim($head), '<plist');
}

/**
 * Resolves SSL certificate and private key paths for auto-signing .mobileconfig.
 * Looks in: 1) config 'ssl', 2) app/ssl/ directory, 3) standard cPanel user SSL directory.
 */
function ipastore_ssl_config(): ?array
{
    $ssl   = cfg('ssl') ?? [];
    $cert  = $ssl['cert'] ?? '';
    $key   = $ssl['key'] ?? '';
    $chain = $ssl['chain'] ?? '';

    // 1. Explicit configuration paths
    if ($cert && $key) {
        $certPath  = (str_starts_with($cert, '/') || preg_match('/^[a-zA-Z]:/', $cert)) ? $cert : __DIR__ . '/../' . ltrim($cert, '/');
        $keyPath   = (str_starts_with($key, '/')  || preg_match('/^[a-zA-Z]:/', $key))  ? $key  : __DIR__ . '/../' . ltrim($key, '/');
        $chainPath = $chain ? ((str_starts_with($chain, '/') || preg_match('/^[a-zA-Z]:/', $chain)) ? $chain : __DIR__ . '/../' . ltrim($chain, '/')) : null;
        if (is_file($certPath) && is_file($keyPath)) {
            return ['cert' => $certPath, 'key' => $keyPath, 'chain' => ($chainPath && is_file($chainPath)) ? $chainPath : null];
        }
    }

    // 2. Default app/ssl/ directory (protected from web access by .htaccess)
    $dir = __DIR__ . '/ssl/';
    if (is_dir($dir)) {
        $foundCert  = null;
        $foundKey   = null;
        $foundChain = null;
        foreach (['cert.crt', 'cert.pem', 'certificate.crt', 'ipagame.store.crt'] as $c) {
            if (is_file($dir . $c)) { $foundCert = $dir . $c; break; }
        }
        foreach (['cert.key', 'privkey.pem', 'private.key', 'ipagame.store.key'] as $k) {
            if (is_file($dir . $k)) { $foundKey = $dir . $k; break; }
        }
        foreach (['chain.crt', 'chain.pem', 'ca.crt', 'cabundle.crt'] as $ch) {
            if (is_file($dir . $ch)) { $foundChain = $dir . $ch; break; }
        }
        if ($foundCert && $foundKey) {
            return ['cert' => $foundCert, 'key' => $foundKey, 'chain' => $foundChain];
        }
    }

    // 3. Auto-detect from cPanel user directory (/home/username/ssl/)
    $home = getenv('HOME') ?: '';
    if (!$home && function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
        $pw = @posix_getpwuid(posix_geteuid());
        $home = $pw['dir'] ?? '';
    }
    if ($home && is_dir($home . '/ssl/certs') && is_dir($home . '/ssl/keys')) {
        $host = parse_url(SITE_URL, PHP_URL_HOST) ?: 'ipagame.store';
        $bestCert  = null;
        $bestMtime = 0;
        foreach (glob($home . '/ssl/certs/*.crt') ?: [] as $cFile) {
            $data = @file_get_contents($cFile);
            if (!$data) continue;
            $x509 = @openssl_x509_parse($data);
            if (!$x509 || ($x509['validTo_time_t'] ?? 0) < time()) continue;
            $cn  = $x509['subject']['CN'] ?? '';
            $alt = $x509['extensions']['subjectAltName'] ?? '';
            if ($cn === $host || str_contains($alt, $host)) {
                $mtime = filemtime($cFile);
                if ($mtime > $bestMtime) {
                    $bestMtime = $mtime;
                    $bestCert  = $cFile;
                }
            }
        }
        if ($bestCert) {
            $base = basename($bestCert, '.crt');
            $matchingKey = null;
            if (is_file($home . '/ssl/keys/' . $base . '.key')) {
                $matchingKey = $home . '/ssl/keys/' . $base . '.key';
            } else {
                $certContent = file_get_contents($bestCert);
                foreach (glob($home . '/ssl/keys/*.key') ?: [] as $kFile) {
                    $kData = @file_get_contents($kFile);
                    if ($kData && @openssl_x509_check_private_key($certContent, $kData)) {
                        $matchingKey = $kFile;
                        break;
                    }
                }
            }
            if ($matchingKey) {
                $chain = is_file($home . '/ssl/certs/' . $base . '.cabundle') ? $home . '/ssl/certs/' . $base . '.cabundle' : null;
                return ['cert' => $bestCert, 'key' => $matchingKey, 'chain' => $chain];
            }
        }
    }

    return null;
}

/**
 * Ensures the mobileconfig profile is signed with the current SSL certificate.
 * Returns the path to the file that should be served.
 */
function ensure_ipastore_profile_signed(): string
{
    $app       = cfg('ipastore_app');
    $servePath = __DIR__ . '/../' . $app['file'];
    $tmplPath  = __DIR__ . '/../downloads/ipastore-template.mobileconfig';

    if (!is_file($tmplPath)) {
        if (is_file($servePath)) {
            @copy($servePath, $tmplPath);
        } else {
            return $servePath;
        }
    }

    $ssl = ipastore_ssl_config();
    if (!$ssl || !function_exists('openssl_cms_sign')) {
        // No SSL config: serve the unsigned template/file as-is
        return is_file($servePath) ? $servePath : $tmplPath;
    }

    $certFile  = $ssl['cert'];
    $keyFile   = $ssl['key'];
    $chainFile = $ssl['chain'] ?? null;

    $needsSign = false;
    if (!is_file($servePath)) {
        $needsSign = true;
    } else {
        $serveMtime = filemtime($servePath);
        $tmplMtime  = filemtime($tmplPath);
        $certMtime  = filemtime($certFile);
        // Re-sign if template or certificate was modified
        if ($serveMtime < $tmplMtime || $serveMtime < $certMtime) {
            $needsSign = true;
        }
        // Re-sign weekly to track certificate auto-renewals
        elseif ((time() - $serveMtime) > (7 * 86400)) {
            $needsSign = true;
        }
        // Re-sign if currently plain XML (unsigned)
        elseif (is_file_xml($servePath)) {
            $needsSign = true;
        }
    }

    if ($needsSign) {
        $certData = @file_get_contents($certFile);
        $keyData  = @file_get_contents($keyFile);
        if ($certData && $keyData) {
            $tempOut = $servePath . '.tmp.' . bin2hex(random_bytes(4));
            $flags   = OPENSSL_CMS_BINARY;
            $ok = @openssl_cms_sign(
                $tmplPath,
                $tempOut,
                $certData,
                $keyData,
                [],
                $flags,
                OPENSSL_ENCODING_DER,
                $chainFile
            );
            if ($ok && is_file($tempOut) && filesize($tempOut) > 1000) {
                @rename($tempOut, $servePath);
            } else {
                @unlink($tempOut);
                if (!is_file($servePath)) {
                    @copy($tmplPath, $servePath);
                }
            }
        }
    }

    return is_file($servePath) ? $servePath : $tmplPath;
}

/**
 * Streams the mobileconfig profile to the client with correct iOS headers.
 */
function serve_ipastore_profile(): void
{
    $path = ensure_ipastore_profile_signed();
    if (!is_file($path)) {
        redirect(url('download-ipastore/'), 302);
    }

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Type: application/x-apple-aspen-config; charset=utf-8');
    header('Content-Disposition: attachment; filename="ipastore.mobileconfig"');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: private, no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('X-Robots-Tag: noindex');

    readfile($path);
    exit;
}

/**
 * Generated SEO title and description for a game page.
 * games.seo_title / seo_description are NOT used: the import filled them with boilerplate for ~every game.
 * Shared by the game route and the /seo-health/ report so they can never disagree.
 */
function game_seo(array $g, bool $isIpa): array
{
    $name = game_name($g);
    $ver  = version_label($g['latest_version']);
    $size = size_label($g['latest_size_mb']);
    $ios  = $g['min_ios'] ? "iOS {$g['min_ios']}" : '';

    $desc = excerpt("$name $ver for iPhone and iPad: $size" . ($ios ? ", $ios or later" : '') . ". Screenshots, what's new, install guide, safety info and full update history.", 158);

    // Keep the title within ~60 characters: the version (and then the brand suffix) drop off first.
    $title = $name . ($isIpa ? ' IPA' : '') . ' for iPhone & iPad';
    if ($ver && mb_strlen("$title ($ver) | " . SITE_NAME) <= 60) $title .= " ($ver)";
    return [$title, $desc];
}

/**
 * Daily download counters per game. kind 'store' = the IPA Game Store app was downloaded after the
 * visitor came from that game's page (game_id 0 = no game, e.g. from the home page); 'ipa' = the game's IPA file.
 * Never throws: tracking must not break a download.
 */
function track_download(string $kind, int $gameId): void
{
    if (preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview|curl|wget/i', $_SERVER['HTTP_USER_AGENT'] ?? '')) return;
    try {
        db()->exec('CREATE TABLE IF NOT EXISTS download_stats (
            day DATE NOT NULL, game_id INT UNSIGNED NOT NULL, kind VARCHAR(8) NOT NULL, n INT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (day, game_id, kind), KEY (kind, game_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        db()->prepare('INSERT INTO download_stats (day, game_id, kind, n) VALUES (CURDATE(), ?, ?, 1) ON DUPLICATE KEY UPDATE n = n + 1')
            ->execute([$gameId, $kind]);
    } catch (Throwable $e) {}
}

/** The game slug passed as ?from= on the IPA Game Store links, or '' if missing or invalid. */
function from_slug(): string
{
    $s = (string) ($_GET['from'] ?? '');
    return preg_match('/^[a-z0-9][a-z0-9-]{0,150}$/', $s) ? $s : '';
}

/**
 * Games ranked by real downloads (IPA Game Store app + IPA file, all time), ties and games without
 * downloads ordered by rating count. Each row gets 'dl' = its download count (0 = no downloads yet).
 * Falls back to the rating order when download tracking has no table yet.
 */
function top_downloaded(int $limit = 10): array
{
    $cols = 'g.' . str_replace(', ', ', g.', preg_replace('/\s+/', ' ', CARD_COLS));
    try {
        $sql = "SELECT $cols, (COALESCE(d.store_n, 0) + GREATEST(COALESCE(d.ipa_n, 0), g.downloads)) AS dl
                FROM games g
                LEFT JOIN (SELECT game_id, SUM(IF(kind='store', n, 0)) AS store_n, SUM(IF(kind='ipa', n, 0)) AS ipa_n
                           FROM download_stats WHERE game_id > 0 GROUP BY game_id) d ON d.game_id = g.id
                WHERE g.status='published' AND g.type='game'
                ORDER BY dl DESC, g.rating_count DESC LIMIT " . (int) $limit;
        return db()->query($sql)->fetchAll();
    } catch (Throwable $e) {
        return array_map(fn($r) => $r + ['dl' => 0], games_where('1=1', 'rating_count DESC', $limit));
    }
}
