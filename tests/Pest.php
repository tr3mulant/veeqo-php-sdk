<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Veeqo;

/** A captured, scrubbed response as a mock (tests/Fixtures/responses). */
function fixture(string $name): MockResponse
{
    $fixture = json_decode(file_get_contents(__DIR__."/Fixtures/responses/$name.json"), true);

    return MockResponse::make($fixture['body'], $fixture['status'], $fixture['headers']);
}

function fixtureBody(string $name): mixed
{
    return json_decode(file_get_contents(__DIR__."/Fixtures/responses/$name.json"), true)['body'];
}

function veeqo(MockClient $mock): Veeqo
{
    $veeqo = Veeqo::withApiKey('test-key');
    $veeqo->retryInterval = 0;

    return $veeqo->withMockClient($mock);
}

/**
 * Wire keys in $json that no property on $object maps and its UNMAPPED list doesn't name (ADR 0006).
 *
 * @return list<string>
 */
function unmappedKeys(array $json, object $object, string $path = ''): array
{
    $missing = [];
    $allowed = defined($object::class.'::UNMAPPED') ? $object::UNMAPPED : [];

    foreach ($json as $key => $value) {
        $property = lcfirst(str_replace('_', '', ucwords(rtrim($key, '?'), '_')));
        if (! property_exists($object, $property)) {
            if (! in_array($key, $allowed, true)) {
                $missing[] = $object::class." $path.$key";
            }

            continue;
        }
        $mapped = $object->$property;
        if (is_object($mapped) && is_array($value) && ! array_is_list($value)) {
            $missing = [...$missing, ...unmappedKeys($value, $mapped, "$path.$key")];
        } elseif (is_array($mapped) && is_array($value)) {
            foreach ($mapped as $i => $item) {
                if (is_object($item)) {
                    $missing = [...$missing, ...unmappedKeys($value[$i], $item, "$path.$key[$i]")];
                }
            }
        }
    }

    return array_values(array_unique($missing));
}
