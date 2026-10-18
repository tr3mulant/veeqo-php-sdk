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

// Replays a response captured by tests/Fixtures/capture.php.
function captured(string $name): MockResponse
{
    /** @var array{status: int, headers: array<string, string>, body: array<mixed>|string} $captured */
    $captured = json_decode((string) file_get_contents(__DIR__."/Fixtures/responses/{$name}.json"), true);

    return MockResponse::make($captured['body'], $captured['status'], $captured['headers']);
}

function connectionFailure(): MockResponse
{
    return MockResponse::make()->throw(fn (PendingRequest $request) => new FatalRequestException(new ConnectException('timed out', new PsrRequest('GET', '/')), $request));
}
