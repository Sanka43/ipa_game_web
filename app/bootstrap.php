<?php
declare(strict_types=1);

$CFG = require __DIR__ . '/../config.php';

if ($CFG['debug']) { ini_set('display_errors', '1'); error_reporting(E_ALL); }
else { ini_set('display_errors', '0'); }

mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');

// Base path of the site (works in a sub-folder like /ipa game site/ and at a domain root).
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));
define('SITE_URL', $CFG['site_url'] !== ''
    ? rtrim($CFG['site_url'], '/')
    : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . str_replace(' ', '%20', BASE_PATH)));
define('SITE_NAME', $CFG['site_name']);

function cfg(string $key) { global $CFG; return $CFG[$key] ?? null; }

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $c = cfg('db');
        $pdo = new PDO("mysql:host={$c['host']};dbname={$c['name']};charset=utf8mb4", $c['user'], $c['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/catalog.php';
require __DIR__ . '/repo.php';
require __DIR__ . '/../views/partials.php';
