<?php

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Requests\HTTPState;

test('Run query with valid API Key should return 200', function () {
    // Arrange
    $apiKey = "valid-api-key";
    $client = DeepSeekClient::build($apiKey)
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    // Act
    $response = $client->run();
    $result = $client->getResult();

    // Assert
    expect($response)->not->toBeEmpty($response)
        ->and($result->getStatusCode())->toEqual(HTTPState::OK->value);
});

test('Run query with valid API Key & insufficient balance should return 402', function () {
    // Arrange
    $apiKey = "insufficient-balance-api-key";
    $client = DeepSeekClient::build($apiKey)
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    // Act
    $response = $client->run();
    $result = $client->getResult();

    // Assert
    expect($response)->not->toBeEmpty($response)
        ->and($result->getStatusCode())->toEqual(HTTPState::PAYMENT_REQUIRED->value);
});

test('Run query with invalid API key should return 401', function () {
    // Arrange
    $apiKey = "insufficient-balance-api-key";
    $client = DeepSeekClient::build($apiKey)
        ->query('Hello DeepSeek, how are you today?')
        ->setTemperature(1.5);

    // Act
    $response = $client->run();
    $result = $client->getResult();

    // Assert
    expect($response)->not->toBeEmpty($response)
        ->and($result->getStatusCode())->toEqual(HTTPState::UNAUTHORIZED->value);
});
