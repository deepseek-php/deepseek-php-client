<?php

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Requests\HTTPState;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;

afterEach(function () {
    Mockery::close();
});

test('Run query with valid API Key should return 200', function () {
    $factory = new Psr17Factory;
    $body = $factory->createStream('{"id":"abc","object":"chat.completion","choices":[{"message":{"content":"Hello!","role":"assistant"}}]}');

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')->once()->andReturn(
        $factory->createResponse(200)->withBody($body)
    );

    $client = (new DeepSeekClient($httpClient))
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    $response = $client->run();
    $result = $client->getResult();

    expect($response)->not->toBeEmpty()
        ->and($result->getStatusCode())->toEqual(HTTPState::OK->value);
});

test('Run query with valid API Key & insufficient balance should return 402', function () {
    $factory = new Psr17Factory;
    $body = $factory->createStream('{"error":{"message":"Insufficient balance","type":"insufficient_quota"}}');

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')->once()->andReturn(
        $factory->createResponse(402)->withBody($body)
    );

    $client = (new DeepSeekClient($httpClient))
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    $response = $client->run();
    $result = $client->getResult();

    expect($response)->not->toBeEmpty()
        ->and($result->getStatusCode())->toEqual(HTTPState::PAYMENT_REQUIRED->value);
});

test('Run query with invalid API key should return 401', function () {
    $factory = new Psr17Factory;
    $body = $factory->createStream('{"error":{"message":"Authentication Fails","type":"authentication_error"}}');

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')->once()->andReturn(
        $factory->createResponse(401)->withBody($body)
    );

    $client = (new DeepSeekClient($httpClient))
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    $response = $client->run();
    $result = $client->getResult();

    expect($response)->not->toBeEmpty()
        ->and($result->getStatusCode())->toEqual(HTTPState::UNAUTHORIZED->value);
});
