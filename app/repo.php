<?php
// Data access. The games table is shared with the ipagame.store app (which also stores apps),
// so every public query is limited to published items of type game.

const CARD_COLS = 'id, slug, name, category, developer, icon, short_description, latest_version, latest_size_mb,
                   latest_release_date, rating_value, rating_count, is_offline, min_ios';

function games_where(string $where, string $order = 'rating_count DESC', int $limit = 12, int $offset = 0): array
{
    $sql = 'SELECT ' . CARD_COLS . " FROM games WHERE status='published' AND type='game' AND ($where) ORDER BY $order LIMIT $limit OFFSET $offset";
    return db()->query($sql)->fetchAll();
}

function games_count(string $where): int
{
    return (int) db()->query("SELECT COUNT(*) FROM games WHERE status='published' AND type='game' AND ($where)")->fetchColumn();
}

function game_by_slug(string $slug): ?array
{
    $st = db()->prepare("SELECT * FROM games WHERE slug=? AND status='published' AND type='game'");
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
    $st = db()->prepare('SELECT ' . CARD_COLS . " FROM games WHERE status='published' AND type='game' AND category=? AND id<>? ORDER BY rating_count DESC LIMIT $limit");
    $st->execute([$g['category'], $g['id']]);
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
function genre_posters(array $slugs): array
{
    $out = array_fill_keys($slugs, '');
    if (!$slugs) return $out;
    $in = implode(',', array_map(fn($s) => db()->quote($s), $slugs));
    $rows = db()->query("SELECT g.category, s.url FROM games g JOIN game_screenshots s ON s.game_id=g.id AND s.sort=0 AND s.device='iphone'
                         WHERE g.status='published' AND g.type='game' AND g.category IN ($in) ORDER BY g.rating_count DESC")->fetchAll();
    foreach ($rows as $r) if ($out[$r['category']] === '') $out[$r['category']] = $r['url'];
    return $out;
}

function genre_counts(): array
{
    return db()->query("SELECT category, COUNT(*) n FROM games WHERE status='published' AND type='game' GROUP BY category")->fetchAll(PDO::FETCH_KEY_PAIR);
}

function search_games(string $q, int $limit = 24, int $offset = 0): array
{
    $q = trim($q);
    if (mb_strlen($q) < 2) return [[], 0];
    $like = '%' . addcslashes($q, '%_\\') . '%';
    $where = "status='published' AND type='game' AND (name LIKE ? OR developer LIKE ?)";
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
    $app  = cfg('ipastore_app');
    $path = __DIR__ . '/../' . $app['file'];
    if (!is_file($path)) return null;
    return ['url' => url($app['file']), 'name' => basename($path), 'size_kb' => (int) ceil(filesize($path) / 1024),
            'updated' => date('Y-m-d', filemtime($path))];
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
