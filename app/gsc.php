<?php
// Google Search Console (Search Analytics) client for /seo-health/, no Google library needed:
// a service account signs a JWT (RS256), trades it for an access token, then we call the API.
// Read-only scope. Config (config.live.php):  'gsc' => ['key_file' => '/abs/path/gsc-key.json', 'site' => 'sc-domain:ipagame.store']

const GSC_SCOPE = 'https://www.googleapis.com/auth/webmasters.readonly';
const GSC_TOKEN = 'https://oauth2.googleapis.com/token';
const GSC_API   = 'https://www.googleapis.com/webmasters/v3/sites/';

function gsc_config(): array
{
    $c = cfg('gsc') ?: [];
    $file = (string) ($c['key_file'] ?? '');
    if ($file !== '' && !preg_match('~^([a-z]:)?[\\\\/]~i', $file)) $file = __DIR__ . '/../' . $file;   // relative → site root
    return ['key_file' => $file, 'site' => (string) ($c['site'] ?? '')];
}

function gsc_configured(): bool { $c = gsc_config(); return $c['site'] !== '' && is_file($c['key_file']); }

function b64url(string $s): string { return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); }

/** POST/GET JSON helper. Throws RuntimeException with Google's own message on failure. */
function gsc_http(string $url, ?array $json = null, ?string $form = null, ?string $bearer = null): array
{
    $ch = curl_init($url);
    $headers = [];
    if ($bearer) $headers[] = "Authorization: Bearer $bearer";
    if ($json !== null) { $headers[] = 'Content-Type: application/json'; curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json)); }
    if ($form !== null) { $headers[] = 'Content-Type: application/x-www-form-urlencoded'; curl_setopt($ch, CURLOPT_POSTFIELDS, $form); }
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30, CURLOPT_HTTPHEADER => $headers, CURLOPT_POST => $json !== null || $form !== null]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($body === false) throw new RuntimeException("Network error: $err");
    $data = json_decode((string) $body, true) ?? [];
    if ($code >= 400) {
        $msg = $data['error']['message'] ?? ($data['error_description'] ?? ($data['error'] ?? "HTTP $code"));
        throw new RuntimeException(is_string($msg) ? $msg : json_encode($msg));
    }
    return $data;
}

function gsc_token(): string
{
    $c = gsc_config();
    $key = json_decode((string) file_get_contents($c['key_file']), true);
    if (empty($key['client_email']) || empty($key['private_key'])) throw new RuntimeException('Key file is not a service-account JSON (client_email / private_key missing).');
    $now = time();
    $unsigned = b64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])) . '.' . b64url(json_encode([
        'iss' => $key['client_email'], 'scope' => GSC_SCOPE, 'aud' => GSC_TOKEN, 'iat' => $now, 'exp' => $now + 3000,
    ]));
    if (!openssl_sign($unsigned, $sig, $key['private_key'], OPENSSL_ALGO_SHA256)) throw new RuntimeException('Could not sign the token: private key is invalid.');
    $res = gsc_http(GSC_TOKEN, null, http_build_query(['grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => "$unsigned." . b64url($sig)]));
    return $res['access_token'] ?? throw new RuntimeException('No access token returned.');
}

/** One Search Analytics query. $dims: [] for totals, or ['query'] / ['page'] ... */
function gsc_query(string $token, string $from, string $to, array $dims = [], int $limit = 25): array
{
    $site = rawurlencode(gsc_config()['site']);
    $res = gsc_http(GSC_API . $site . '/searchAnalytics/query', [
        'startDate' => $from, 'endDate' => $to, 'dimensions' => $dims, 'rowLimit' => $limit, 'dataState' => 'final',
    ], null, $token);
    return $res['rows'] ?? [];
}

/** Everything the report needs. Search Console data lags ~2–3 days, so the window ends 3 days ago. */
function gsc_report(int $days = 28): array
{
    $token = gsc_token();
    $end   = strtotime('-3 days');
    $from  = date('Y-m-d', strtotime('-' . ($days - 1) . ' days', $end));
    $to    = date('Y-m-d', $end);
    $pTo   = date('Y-m-d', strtotime('-1 day', strtotime($from)));
    $pFrom = date('Y-m-d', strtotime('-' . ($days - 1) . ' days', strtotime($pTo)));

    $tot  = gsc_query($token, $from, $to)[0] ?? ['clicks' => 0, 'impressions' => 0, 'ctr' => 0, 'position' => 0];
    $prev = gsc_query($token, $pFrom, $pTo)[0] ?? ['clicks' => 0, 'impressions' => 0, 'ctr' => 0, 'position' => 0];
    $queries = gsc_query($token, $from, $to, ['query'], 25);
    $pages   = gsc_query($token, $from, $to, ['page'], 500);

    $sitemaps = [];
    try {
        $sm = gsc_http(GSC_API . rawurlencode(gsc_config()['site']) . '/sitemaps', null, null, $token);
        foreach ($sm['sitemap'] ?? [] as $s)
            $sitemaps[] = ['path' => $s['path'] ?? '', 'errors' => (int) ($s['errors'] ?? 0), 'warnings' => (int) ($s['warnings'] ?? 0),
                           'submitted' => array_sum(array_map(fn($c) => (int) ($c['submitted'] ?? 0), $s['contents'] ?? [])),
                           'pending' => !empty($s['isPending']), 'last' => $s['lastDownloaded'] ?? ''];
    } catch (RuntimeException $e) { /* sitemap list is optional */ }

    return ['from' => $from, 'to' => $to, 'days' => $days, 'tot' => $tot, 'prev' => $prev, 'queries' => $queries, 'pages' => $pages, 'sitemaps' => $sitemaps];
}

/** Pages worth working on, from the page rows. Returns [lowCtr, striking]. */
function gsc_opportunities(array $pages): array
{
    $lowCtr = array_values(array_filter($pages, fn($r) => $r['impressions'] >= 30 && $r['position'] <= 10 && $r['ctr'] < 0.03));
    usort($lowCtr, fn($a, $b) => $b['impressions'] <=> $a['impressions']);
    $striking = array_values(array_filter($pages, fn($r) => $r['impressions'] >= 10 && $r['position'] > 5 && $r['position'] <= 20));
    usort($striking, fn($a, $b) => $b['impressions'] <=> $a['impressions']);
    return [array_slice($lowCtr, 0, 25), array_slice($striking, 0, 25)];
}
