<?php
namespace Tests\Feature;

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Requests\HTTPState;
use Mockery;
use Mockery\{LegacyMockInterface,MockInterface};
use Tests\Feature\ClientDependency\FakeResponse;


function get_weather($city)
{
    $city = strtolower($city);
    $city = match($city){
        "cairo" => ["temperature"=> 22, "condition" => "Sunny"],
        "gharbia" => ["temperature"=> 23, "condition" => "Sunny"],
        "sharkia" => ["temperature"=> 24, "condition" => "Sunny"],
        "beheira" => ["temperature"=> 21, "condition" => "Sunny"],
        default => "not found city name."
    };
    return json_encode($city);
}

test('Test function calling with fake responses.', function () {
    // Arrange
    $fake = new FakeResponse();

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
                "type" => "function",
                "function" => [
                    "name" => "get_weather",
                    "description" => "Get the current weather in a given city",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "city" => [
                                "type" => "string",
                                "description" => "The city name",
                            ],
                        ],
                        "required" => ["city"],
                    ],
                ],
            ],
        ]
    )->run();

    // Assert
    expect($fake->toolFunctionCalling())->toEqual($response);
    
    //------------------------------------------
    
    // Arrange    
    $response = json_decode($response, true);
    $message = $response['choices'][0]['message'];
    
    $firstFunction = $message['tool_calls'][0];
        if ($firstFunction['function']['name'] == "get_weather")
    {
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

test('Test function calling use base data with real responses.', function () {
    // Arrange
    $client = DeepSeekClient::build('your-api-key')
        ->query('What is the weather like in Cairo?')
        ->setTools([
            [
                "type" => "function",
                "function" => [
                    "name" => "get_weather",
                    "description" => "Get the current weather in a given city",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "city" => [
                                "type" => "string",
                                "description" => "The city name",
                            ],
                        ],
                        "required" => ["city"],
                    ],
                ],
            ],
        ]
    );
    
    // Act
    $response = $client->run();
    $result = $client->getResult();

    // Assert
    expect($response)->not()->toBeEmpty($response)
        ->and($result->getStatusCode())->toEqual(HTTPState::OK->value);

    //-----------------------------------------------------------------

    // Arrange
    $response = json_decode($response, true);

    $message = $response['choices'][0]['message'];
    $firstFunction = $message['tool_calls'][0];
    if ($firstFunction['function']['name'] == "get_weather")
    {
        $args = json_decode($firstFunction['function']['arguments'], true);
        $weather_data = get_weather($args['city']);
    }
    
    $client2 = $client->queryToolCall(
            $message['tool_calls'],
            $message['content'],
            $message['role']
        )->queryTool(
            $firstFunction['id'],
            $weather_data,
            'tool'
    );
    
    // Act
    $response2 = $client2->run();
    $result2 = $client2->getResult();

    // Assert
    expect($response2)->not()->toBeEmpty($response2)
        ->and($result2->getStatusCode())->toEqual(HTTPState::OK->value);
});
