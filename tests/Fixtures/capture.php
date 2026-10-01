<?php

/**
 * Capture scrubbed fixtures from the live Veeqo API. GET only: the key is a production account.
 *
 *   php tests/Fixtures/capture.php [--burst]   capture (needs VEEQO_API_KEY in env or .env)
 *   php tests/Fixtures/capture.php --rescrub   re-apply the scrub to existing fixtures, no API calls
 *
 * Raw responses never touch disk: each one is scrubbed in memory and written to
 * tests/Fixtures/responses/<name>.json as {request, status, headers, body}. --burst also fires
 * concurrent GETs to capture a real 429.
 *
 * The scrub is an allowlist. Fixtures keep the real shape, types and nullability, and nothing that
 * identifies the account:
 * - null and booleans pass through.
 * - Integer ids (id, *_id, *_ids, numeric path segments) become sequential fakes, consistent across
 *   files within a run so cross-references still match. UUIDs likewise.
 * - Other numbers and numeric strings keep their form with every non-zero digit set to 1
 *   (15386 -> 11111, "12.50" -> "11.10"), so type, zero-ness and magnitude survive.
 * - Dates keep their format at a fixed instant: 2020-01-01, midnight, UTC.
 * - Enum-like tokens (lowercase snake) and 2-3 letter upper-case codes (USD, US, NY) survive,
 *   unless the key has a PII or secret segment (name, email, address, token, ...).
 * - Every other string becomes a salted-HMAC placeholder (equal inputs match within a run).
 * - Headers are cut to content-type, content-length and pagination; pagination totals are faked.
 * Error bodies (status >= 400) come from our own bad requests and are kept verbatim.
 * After a run, review the "kept" report it prints before committing.
 */

$root = dirname(__DIR__, 2);
$out = __DIR__.'/responses';
$salt = random_bytes(32);
$kept = [];
$fakeIds = [];
$fakeUuids = [];

const PII = ['name', 'names', 'email', 'emails', 'phone', 'mobile', 'fax', 'address', 'address1', 'address2',
    'address3', 'line1', 'line2', 'line3', 'city', 'town', 'county', 'zip', 'zipcode', 'postcode', 'postal',
    'company', 'first', 'last', 'contact', 'note', 'notes', 'comment', 'comments', 'instructions', 'ip',
    'latitude', 'longitude', 'lat', 'lng', 'tracking', 'token', 'secret', 'password', 'key', 'credentials',
    'signature', 'vat', 'eori', 'iban', 'username', 'login', 'dob', 'birthday', 'nickname', 'title',
    'description', 'subject', 'body', 'reference', 'buyer', 'recipient', 'sender', 'url', 'domain', 'website'];

const KEPT_HEADERS = ['content-type', 'content-length', 'x-page-index', 'x-per-page', 'x-total-count', 'x-total-pages-count'];

function placeholder(string $v): string
{
    global $salt;
    $h = substr(hash_hmac('sha256', $v, $salt), 0, 8);

    return match (true) {
        str_contains($v, '@') => "redacted-$h@example.com",
        str_starts_with($v, 'http') => "https://example.com/redacted-$h",
        default => "redacted-$h",
    };
}

function fakeId(int $id): int
{
    global $fakeIds;

    return $fakeIds[$id] ??= 1000 + count($fakeIds);
}

function fakeUuid(string $uuid): string
{
    global $fakeUuids;

    return $fakeUuids[$uuid] ??= sprintf('00000000-0000-4000-8000-%012d', count($fakeUuids) + 1);
}

function ones(string $digits): string
{
    return preg_replace('/[1-9]/', '1', $digits);
}

function scrub(mixed $v, string $key = ''): mixed
{
    global $kept;
    if (is_array($v)) {
        $r = [];
        foreach ($v as $k => $x) {
            $r[$k] = scrub($x, is_string($k) ? strtolower($k) : $key);
        }

        return $r;
    }
    if ($v === null || is_bool($v)) {
        return $v;
    }
    if (is_int($v) && ($key === 'id' || str_ends_with($key, '_id') || str_ends_with($key, '_ids'))) {
        return fakeId($v);
    }
    if (is_int($v) || is_float($v)) {
        return json_decode(ones(json_encode($v)));
    }
    $pii = array_intersect(explode('_', $key), PII);
    if ($v === '' || (! $pii && preg_match('/^-?\d+(\.\d+)?$/', $v))) {
        return ones($v);
    }
    if (preg_match('/^[0-9a-f]{8}(-[0-9a-f]{4}){3}-[0-9a-f]{12}$/', $v)) {
        return fakeUuid($v);
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}(?:([T ])([\d:.]+)(Z|[+-]\d{2}:?\d{2})?)?$/', $v, $m)) {
        $zone = $m[3] ?? '';

        return '2020-01-01'.(isset($m[1]) ? $m[1].preg_replace('/\d/', '0', $m[2]).preg_replace('/\d/', '0', $zone) : '');
    }
    if (! $pii && (preg_match('/^[a-z][a-z0-9_]{0,39}$/', $v) || preg_match('/^[A-Z]{2,3}$/', $v))) {
        $kept[$key][$v] = true;

        return $v;
    }

    return placeholder($v);
}

function sanitize(array $r): array
{
    $r['request']['path'] = preg_replace_callback('#/(\d+)(?=/|\.|$)#', fn ($m) => '/'.fakeId((int) $m[1]), $r['request']['path']);
    $headers = array_intersect_key($r['headers'], array_flip(KEPT_HEADERS));
    if (isset($headers['x-total-count'])) {
        $headers['x-total-count'] = ones($headers['x-total-count']);
        $headers['x-total-pages-count'] = (string) (int) ceil((int) $headers['x-total-count'] / max(1, (int) ($headers['x-per-page'] ?? 1)));
    }
    $r['headers'] = $headers;
    if ($r['status'] < 400) {
        $r['body'] = scrub($r['body']);
    }

    return $r;
}

function write(string $name, array $r): void
{
    global $out;
    @mkdir($out, 0777, true);
    file_put_contents("$out/$name.json", json_encode(sanitize($r), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION)."\n");
}

function report(): void
{
    global $kept;
    echo "\nKept string values (review before committing):\n";
    foreach ($kept as $k => $values) {
        echo "  $k: ", implode(', ', array_slice(array_keys($values), 0, 15)), "\n";
    }
}

if (in_array('--rescrub', $argv, true)) {
    foreach (glob("$out/*.json") as $file) {
        write(basename($file, '.json'), json_decode(file_get_contents($file), true));
    }
    report();
    exit;
}

$key = getenv('VEEQO_API_KEY') ?: (parse_ini_file($root.'/.env')['VEEQO_API_KEY'] ?? null);
$key or exit("VEEQO_API_KEY not set\n");

function request(string $path, array $query = [], ?string $apiKey = null): array
{
    global $key;
    $url = 'https://api.veeqo.com'.$path.($query ? '?'.http_build_query($query) : '');
    for ($try = 0; ; $try++) {
        $headers = [];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['x-api-key: '.($apiKey ?? $key), 'Accept: application/json'],
            CURLOPT_HEADERFUNCTION => function ($ch, $line) use (&$headers) {
                if (str_contains($line, ':')) {
                    [$h, $val] = explode(':', $line, 2);
                    $headers[strtolower(trim($h))] = trim($val);
                }

                return strlen($line);
            },
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        usleep(250_000);
        if ($status !== 429 || $try >= 3) {
            break;
        }
        sleep(2);
    }

    return ['request' => ['method' => 'GET', 'path' => $path, 'query' => $query], 'status' => $status,
        'headers' => $headers, 'body' => json_decode((string) $body, true) ?? $body];
}

/** Writes the scrubbed fixture; returns the raw response so follow-up requests can use real ids. */
function save(string $name, array $r): array
{
    // A success capture that failed (plan-gated 403, rates 400) is an error fixture.
    if ($r['status'] >= 400 && ! str_starts_with($name, 'errors.')) {
        $name = "errors.{$r['status']}.$name";
    }
    write($name, $r);
    echo str_pad($name, 44), $r['status'], is_array($r['body']) && array_is_list($r['body']) ? ' ('.count($r['body']).' items)' : '', "\n";

    return $r;
}

/** First value at a dotted path across a list of records, e.g. find($orders, 'allocations.0.shipment.id'). */
function find(mixed $list, string $path): mixed
{
    foreach (is_array($list) ? $list : [] as $item) {
        $v = $item;
        foreach (explode('.', $path) as $seg) {
            $v = is_array($v) ? ($v[$seg] ?? null) : null;
        }
        if ($v !== null) {
            return $v;
        }
    }

    return null;
}

// Lists, then a show for the first id in each.
$lists = [];
foreach (['orders', 'customers', 'products', 'channels', 'delivery_methods', 'suppliers', 'tags', 'warehouses', 'purchase_orders'] as $res) {
    $lists[$res] = save("$res.list", request("/$res", ['page_size' => 10]))['body'];
    if ($id = find($lists[$res], 'id')) {
        save("$res.show", request("/$res/$id"));
    }
}
$shipped = save('orders.list.shipped', request('/orders', ['page_size' => 10, 'status' => 'shipped']))['body'];
save('current_company.show', request('/current_company'));

if ($id = find($lists['orders'], 'id')) {
    save('orders.returns', request("/orders/$id/returns"));
}
if ($id = find($lists['orders'], 'allocations.0.id')) {
    save('shipping.rates', request("/shipping/rates/$id"));
}
if ($id = find($shipped, 'allocations.0.shipment.id')) {
    save('shipping.tracking_events', request("/shipping/tracking_events/$id"));
}
$product = find($lists['products'], 'id');
if ($product && ($prop = find($lists['products'], 'product_property_specifics.0.id'))) {
    save('products.property_specifics.show', request("/products/$product/product_property_specifics/$prop"));
}
$sellable = find($lists['products'], 'sellables.0.id');
$warehouse = find($lists['warehouses'], 'id');
if ($sellable && $warehouse) {
    save('stock_entry.show', request("/sellables/$sellable/warehouses/$warehouse/stock_entry"));
}

// Errors, by read-only means only.
save('errors.401', request('/current_company', [], 'invalid-key'));
save('errors.404.order', request('/orders/1'));
save('errors.404.product', request('/products/1'));
save('errors.404.route', request('/no_such_resource'));
save('errors.400.orders.bad_page_size', request('/orders', ['page_size' => 'abc']));
// Malformed filters are not errors: an unparseable date is ignored, an unknown status matches nothing.
save('orders.list.bad_date_ignored', request('/orders', ['page_size' => 10, 'created_at_min' => 'not-a-date']));
save('orders.list.unknown_status', request('/orders', ['status' => 'not_a_status']));

if (in_array('--burst', $argv, true)) {
    // Bucket of 100 leaking 5/s, scoped to this key: 150 concurrent GETs should overflow it.
    $mh = curl_multi_init();
    $handles = [];
    for ($i = 0; $i < 150; $i++) {
        $ch = curl_init('https://api.veeqo.com/current_company');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => ["x-api-key: $key", 'Accept: application/json']]);
        curl_multi_add_handle($mh, $handles[] = $ch);
    }
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running);
    $counts = [];
    foreach ($handles as $ch) {
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $counts[$status] = ($counts[$status] ?? 0) + 1;
        if ($status === 429 && ! isset($limited)) {
            [$head, $body] = explode("\r\n\r\n", curl_multi_getcontent($ch), 2);
            $headers = [];
            foreach (array_slice(explode("\r\n", $head), 1) as $line) {
                [$h, $val] = explode(':', $line, 2);
                $headers[strtolower(trim($h))] = trim($val);
            }
            $limited = save('errors.429', ['request' => ['method' => 'GET', 'path' => '/current_company', 'query' => []],
                'status' => 429, 'headers' => $headers, 'body' => json_decode($body, true) ?? $body]);
        }
    }
    ksort($counts);
    echo 'burst statuses: ', json_encode($counts), "\n";
}

report();
