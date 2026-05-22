<?php

namespace Tests\Feature;

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Configs\DefaultConfigs;
use DeepSeek\Enums\Models;
use DeepSeek\Models\SuccessResult;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

afterEach(function () {
    Mockery::close();
});

test('V4 model enum cases are defined with the correct API ids', function () {
    expect(Models::V4_PRO->value)->toBe('deepseek-v4-pro')
        ->and(Models::V4_FLASH->value)->toBe('deepseek-v4-flash');
});

test('default base URL no longer includes the /v3 suffix', function () {
    expect(DefaultConfigs::BASE_URL->value)->toBe('https://api.deepseek.com');
});

test('legacy Models cases remain functional for backward compatibility', function () {
    expect(Models::CHAT->value)->toBe('deepseek-chat')
        ->and(Models::CODER->value)->toBe('deepseek-coder')
        ->and(Models::R1->value)->toBe('DeepSeek-R1')
        ->and(Models::R1Zero->value)->toBe('DeepSeek-R1-Zero');
});

test('SuccessResult strips leading empty lines (non-streaming keep-alive)', function () {
    $factory = new Psr17Factory;
    $body = $factory->createStream("\n\n\n".'{"id":"abc","object":"chat.completion"}');
    $response = $factory->createResponse(200)->withBody($body);

    $result = (new SuccessResult)->setResponse($response);

    expect($result->getContent())->toBe('{"id":"abc","object":"chat.completion"}');
});

test('SuccessResult strips ": keep-alive" SSE comments (streaming)', function () {
    $factory = new Psr17Factory;
    $body = $factory->createStream(
        "data: {\"chunk\":1}\n".
        ": keep-alive\n".
        "data: {\"chunk\":2}\n".
        ": keep-alive\n".
        "data: [DONE]\n"
    );
    $response = $factory->createResponse(200)->withBody($body);

    $result = (new SuccessResult)->setResponse($response);

    expect($result->getContent())->not->toContain(': keep-alive')
        ->and($result->getContent())->toContain('data: {"chunk":1}')
        ->and($result->getContent())->toContain('data: {"chunk":2}')
        ->and($result->getContent())->toContain('data: [DONE]');
});

test('SuccessResult does not corrupt already-clean responses', function () {
    $factory = new Psr17Factory;
    $clean = '{"id":"xyz","object":"chat.completion","choices":[]}';
    $body = $factory->createStream($clean);
    $response = $factory->createResponse(200)->withBody($body);

    $result = (new SuccessResult)->setResponse($response);

    expect($result->getContent())->toBe($clean);
});

test('chat() shortcut includes temperature, max_tokens, tools, response_format in the request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')
        ->once()
        ->andReturnUsing(function (RequestInterface $request) use ($factory, &$capturedBody): ResponseInterface {
            $capturedBody = (string) $request->getBody();

            return $factory->createResponse(200)->withBody($factory->createStream('{"id":"ok"}'));
        });

    $tools = [[
        'type' => 'function',
        'function' => ['name' => 'noop', 'description' => 'noop'],
    ]];

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_FLASH->value)
        ->setTemperature(0.7)
        ->setMaxTokens(2048)
        ->setResponseFormat('text')
        ->setTools($tools)
        ->query('Hello')
        ->chat();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toBeArray()
        ->toHaveKeys(['messages', 'model', 'stream', 'temperature', 'max_tokens', 'tools', 'response_format'])
        ->and($decoded['model'])->toBe('deepseek-v4-flash')
        ->and($decoded['temperature'])->toBe(0.7)
        ->and($decoded['max_tokens'])->toBe(2048)
        ->and($decoded['response_format'])->toBe(['type' => 'text'])
        ->and($decoded['tools'])->toBe($tools);
});

test('code() shortcut includes temperature, max_tokens, tools, response_format in the request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')
        ->once()
        ->andReturnUsing(function (RequestInterface $request) use ($factory, &$capturedBody): ResponseInterface {
            $capturedBody = (string) $request->getBody();

            return $factory->createResponse(200)->withBody($factory->createStream('{"id":"ok"}'));
        });

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_PRO->value)
        ->setTemperature(0.5)
        ->setMaxTokens(1024)
        ->query('def fib(n):')
        ->code();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toBeArray()
        ->toHaveKeys(['messages', 'model', 'stream', 'temperature', 'max_tokens', 'response_format'])
        ->and($decoded['model'])->toBe('deepseek-v4-pro')
        ->and($decoded['temperature'])->toBe(0.5)
        ->and($decoded['max_tokens'])->toBe(1024);
});
