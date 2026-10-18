<?php

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request as PsrRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Veeqo;

function veeqo(MockClient $mock): Veeqo
{
    $veeqo = Veeqo::withApiKey('test-key');
    $veeqo->retryInterval = 0;

    return $veeqo->withMockClient($mock);
}

/**
 * @param  string  $name  'responses/<name>' (captured) or 'docs/<slug>' (Veeqo's doc examples, ADR 0011)
 */
function fixtureResponse(string $name): MockResponse
{
    /** @var array{status: int, headers: array<string, string>, body: array<mixed>|string} $fixture */
    $fixture = json_decode((string) file_get_contents(__DIR__."/Fixtures/{$name}.json"), true);

    return MockResponse::make($fixture['body'], $fixture['status'], $fixture['headers']);
}

function connectionFailure(): MockResponse
{
    return MockResponse::make()->throw(fn (PendingRequest $request) => new FatalRequestException(new ConnectException('timed out', new PsrRequest('GET', '/')), $request));
}
