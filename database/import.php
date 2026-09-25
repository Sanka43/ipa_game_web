<?php
// Creates the database and imports apps.json.  Usage:  php database/import.php [path/to/apps.json]
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only'); }

$cfg  = require __DIR__ . '/../config.php';
$file = $argv[1] ?? __DIR__ . '/apps.json';
$data = json_decode(file_get_contents($file), true) or exit("Cannot read $file\n");

$db = $cfg['db'];
$pdo = new PDO("mysql:host={$db['host']};charset=utf8mb4", $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `{$db['name']}`");
$pdo->exec(file_get_contents(__DIR__ . '/schema.sql'));

$dt = fn($s) => $s ? date('Y-m-d H:i:s', strtotime($s)) : date('Y-m-d H:i:s');
$d  = fn($s) => $s ? date('Y-m-d', strtotime($s)) : null;

$upsert = $pdo->prepare('INSERT INTO games
  (slug,type,name,category,developer,bundle_id,app_store_id,app_store_url,price,icon,short_description,description,
   min_ios,content_rating,languages,is_iphone,is_ipad,is_offline,is_popular,is_editors_choice,rating_value,rating_count,
   license_type,ipa_url,ipa_sha256,latest_version,latest_size_mb,latest_release_date,seo_title,seo_description,status,created_at,updated_at)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
  ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), type=VALUES(type), name=VALUES(name), category=VALUES(category),
   developer=VALUES(developer), bundle_id=VALUES(bundle_id), app_store_id=VALUES(app_store_id), app_store_url=VALUES(app_store_url),
   price=VALUES(price), icon=VALUES(icon), short_description=VALUES(short_description), description=VALUES(description),
   min_ios=VALUES(min_ios), content_rating=VALUES(content_rating), languages=VALUES(languages), is_iphone=VALUES(is_iphone),
   is_ipad=VALUES(is_ipad), is_offline=VALUES(is_offline), rating_value=VALUES(rating_value), rating_count=VALUES(rating_count),
   ipa_url=COALESCE(VALUES(ipa_url), ipa_url), ipa_sha256=COALESCE(VALUES(ipa_sha256), ipa_sha256),
   latest_version=VALUES(latest_version), latest_size_mb=VALUES(latest_size_mb), latest_release_date=VALUES(latest_release_date),
   seo_title=VALUES(seo_title), seo_description=VALUES(seo_description), updated_at=VALUES(updated_at)');
$delShots = $pdo->prepare('DELETE FROM game_screenshots WHERE game_id=?');
$delVers  = $pdo->prepare('DELETE FROM game_versions WHERE game_id=?');
$addShot  = $pdo->prepare('INSERT INTO game_screenshots (game_id,device,url,sort) VALUES (?,?,?,?)');
$addVer   = $pdo->prepare('INSERT INTO game_versions (game_id,version,release_date,size_mb,changelog,download_url) VALUES (?,?,?,?,?,?)');

$counts = ['published' => 0, 'review' => 0];
$pdo->beginTransaction();
foreach ($data['items'] as $it) {
    if (($it['type'] ?? 'game') !== 'game') continue;          // games-only store
    // URLs are built as /ipa-games/{cat}/{slug}-ipa/, so store the slug without the suffix.
    $it['slug'] = preg_replace('/-ipa$/', '', $it['slug']);
    $versions = $it['versions'] ?? [];
    $latest   = $versions[0] ?? [];
    $compat   = $it['compatible'] ?? ['iphone'];
    $offline  = in_array('offline', $it['tags'] ?? [], true)
             || preg_match('/offline|no wi-?fi|without (the )?internet/i', $it['description'] ?? '');

    // Rights layer: anything not pointing at the App Store needs a human to confirm we may distribute it.
    $offStore = false;
    foreach ($versions as $v) if (!str_contains($v['download_url'] ?? '', 'apps.apple.com')) $offStore = true;
    $license = $offStore ? 'unverified' : 'app-store-link';
    $status  = $offStore ? 'review' : ($it['status'] ?? 'published');

    $upsert->execute([
        $it['slug'], $it['type'] ?? 'game', $it['name'], $it['category'], $it['developer'] ?? '',
        $it['bundle_id'] ?? null, $it['app_store_id'] ?? null, $it['app_store_url'] ?? null, $it['price'] ?? 0,
        $it['icon'] ?? '', mb_substr($it['short_description'] ?? '', 0, 500), $it['description'] ?? '',
        $it['min_ios'] ?? '', $it['content_rating'] ?? '', implode(',', $it['languages'] ?? []),
        (int) in_array('iphone', $compat, true), (int) in_array('ipad', $compat, true), (int) $offline,
        (int) !empty($it['featured']['popular']), (int) !empty($it['featured']['editors_choice']),
        $it['rating']['value'] ?? 0, $it['rating']['count'] ?? 0,
        $license, $it['ipa_url'] ?? ($latest['ipa_url'] ?? null), $it['ipa_sha256'] ?? ($latest['sha256'] ?? null),
        $it['latest_version'] ?? ($latest['version'] ?? ''), $latest['size_mb'] ?? 0, $d($latest['release_date'] ?? null),
        $it['seo']['title'] ?? '', $it['seo']['meta_description'] ?? '', $status,
        $dt($it['created_at'] ?? null), $dt($it['updated_at'] ?? null),
    ]);
    $id = (int) $pdo->lastInsertId();

    $delShots->execute([$id]);
    foreach (['iphone' => 'screenshots', 'ipad' => 'ipad_screenshots'] as $dev => $key)
        foreach (($it[$key] ?? []) as $i => $url) $addShot->execute([$id, $dev, $url, $i]);

    $delVers->execute([$id]);
    foreach ($versions as $v)
        $addVer->execute([$id, $v['version'], $d($v['release_date'] ?? null), $v['size_mb'] ?? 0, $v['changelog'] ?? '', $v['download_url']]);

    $counts[$status] = ($counts[$status] ?? 0) + 1;
}

// Hero slots: the most-rated games until an admin picks them by hand.
$pdo->exec("UPDATE games SET is_hero=0");
$pdo->exec("UPDATE games g JOIN (SELECT id FROM games WHERE status='published' AND type='game' ORDER BY rating_count DESC LIMIT 6) t ON t.id=g.id SET g.is_hero=1");
$pdo->commit();

foreach ($counts as $k => $n) echo str_pad($k, 10), $n, "\n";
echo "Done.\n";
