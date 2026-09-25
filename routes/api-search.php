<?php
// Live search suggestions for the header search box.
header('Content-Type: application/json; charset=utf-8');
header('X-Robots-Tag: noindex');
[$games] = search_games((string) ($_GET['q'] ?? ''), 6);
echo json_encode(array_map(fn($g) => [
    'name' => $g['name'],
    'dev'  => $g['developer'],
    'icon' => img($g['icon'], '96x96'),
    'url'  => game_url($g),
    'ver'  => version_label($g['latest_version']),
], $games), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
