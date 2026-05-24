<?php

namespace Tests\Feature;

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Requests\HTTPState;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Tests\Feature\ClientDependency\FakeResponse;

function get_weather($city)
{
    $city = strtolower($city);
    $city = match ($city) {
        'cairo' => ['temperature' => 22, 'condition' => 'Sunny'],
        'gharbia' => ['temperature' => 23, 'condition' => 'Sunny'],
        'sharkia' => ['temperature' => 24, 'condition' => 'Sunny'],
        'beheira' => ['temperature' => 21, 'condition' => 'Sunny'],
        default => 'not found city name.'
    };

    return json_encode($city);
}

test('Test function calling with fake responses.', function () {
    // Arrange
    $fake = new FakeResponse;

    /** @var DeepSeekClient&LegacyMockInterface&MockInterface */
    $mockClient = Mockery::mock(DeepSeekClient::class);

    $mockClient->shouldReceive('build')->andReturn($mockClient);
    $mockClient->shouldReceive('setTools')->andReturn($mockClient);
    $mockClient->shouldReceive('query')->andReturn($mockClient);
    $mockClient->shouldReceive('run')->once()->andReturn($fake->toolFunctionCalling());

    // Act
    $response = $mockClient::build('your-api-key')
        ->query('What is the weather like in Cairo?')
        ->setTools([
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_weather',
                    'description' => 'Get the current weather in a given city',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'city' => [
                                'type' => 'string',
                                'description' => 'The city name',
                            ],
                        ],
                        'required' => ['city'],
                    ],
                ],
            ],
        ]
        )->run();

    // Assert
    expect($fake->toolFunctionCalling())->toEqual($response);

    // ------------------------------------------

    // Arrange
    $response = json_decode($response, true);
    $message = $response['choices'][0]['message'];

    $firstFunction = $message['tool_calls'][0];
    if ($firstFunction['function']['name'] == 'get_weather') {
        $weather_data = get_weather($firstFunction['function']['arguments']['city']);
    }

    $mockClient->shouldReceive('queryCallTool')->andReturn($mockClient);
    $mockClient->shouldReceive('queryTool')->andReturn($mockClient);
    $mockClient->shouldReceive('run')->andReturn($fake->resultToolFunctionCalling());

    // Act
    $response2 = $mockClient->queryCallTool(
        $message['tool_calls'],
        $message['content'],
        $message['role']
    )->queryTool(
        $firstFunction['id'],
        $weather_data,
        'tool'
    )->run();

    // Assert
    expect($fake->resultToolFunctionCalling())->toEqual($response2);
});

afterEach(function () {
    Mockery::close();
});

test('Test function calling use base data with mocked responses.', function () {
    $factory = new Psr17Factory;

    // First HTTP response: model asks to call get_weather
    $toolCallBody = (string) json_encode([
        'id' => 'test-fc-1',
        'choices' => [[
            'finish_reason' => 'tool_calls',
            'index' => 0,
            'message' => [
                'content' => '',
                'role' => 'assistant',
                'tool_calls' => [[
                    'id' => 'call-abc123',
                    'type' => 'function',
                    'function' => [
                        'name' => 'get_weather',
                        'arguments' => json_encode(['city' => 'Cairo']),
                    ],
                ]],
            ],
        ]],
        'usage' => ['completion_tokens' => 12, 'prompt_tokens' => 20, 'total_tokens' => 32],
    ]);

    // Second HTTP response: model returns final answer after receiving tool result
    $finalBody = (string) json_encode([
        'id' => 'test-fc-2',
        'choices' => [[
            'finish_reason' => 'stop',
            'index' => 0,
            'message' => [
                'content' => 'The weather in Cairo is sunny with a temperature of 22 degrees.',
                'role' => 'assistant',
            ],
        ]],
        'usage' => ['completion_tokens' => 20, 'prompt_tokens' => 40, 'total_tokens' => 60],
    ]);

    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')
        ->andReturn(
            $factory->createResponse(200)->withBody($factory->createStream($toolCallBody)),
            $factory->createResponse(200)->withBody($factory->createStream($finalBody))
        );

    $client = new DeepSeekClient($httpClient);

    // Act — first call: model responds with a tool call
    $response = $client
        ->query('What is the weather like in Cairo?')
        ->setTools([[
            'type' => 'function',
            'function' => [
                'name' => 'get_weather',
                'description' => 'Get the current weather in a given city',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => ['type' => 'string', 'description' => 'The city name'],
                    ],
                    'required' => ['city'],
                ],
            ],
        ]])
        ->run();
    $result = $client->getResult();

    // Assert first response
    expect($response)->not()->toBeEmpty()
        ->and($result->getStatusCode())->toEqual(HTTPState::OK->value);

    // Execute the tool locally
    $decoded = json_decode($response, true);
    $message = $decoded['choices'][0]['message'];
    $firstFunction = $message['tool_calls'][0];
    $weather_data = '';
    if ($firstFunction['function']['name'] === 'get_weather') {
        $args = json_decode($firstFunction['function']['arguments'], true);
        $weather_data = get_weather($args['city']);
    }

    // Act — second call: send tool result back to model
    $response2 = $client
        ->queryToolCall($message['tool_calls'], $message['content'], $message['role'])
        ->queryTool($firstFunction['id'], $weather_data, 'tool')
        ->run();
    $result2 = $client->getResult();

    // Assert final response
    expect($response2)->not()->toBeEmpty()
        ->and($result2->getStatusCode())->toEqual(HTTPState::OK->value);
});
