## Function Calling

Function Calling allows the model to call external tools to enhance its capabilities.[[1]](https://api-docs.deepseek.com/guides/function_calling)

#### 1. Define the tools used by the model and pass them with each message passed to the model, Receive query messages from the end user and pass them to the model with the defined tools.
- example function `get_weather($city)`.
```php
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
```
The user requests the weather in Cairo.
```php
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

$response = $client->run();

```

Output response like.
```json
{
    "id": "chat_12345",
    "object": "chat.completion",
    "created": 1677654321,
    "model": "deepseek-chat",
    "choices": [
        {
            "index": 0,
            "message": {
                "role": "assistant",
                "content": null,
                "tool_calls": [
                    {
                        "id": "call_12345",
                        "type": "function",
                        "function": {
                            "name": "get_weather",
                            "arguments": "{\"city\": \"Cairo\"}"
                        }
                    }
                ]
            },
            "finish_reason": "tool_calls"
        }
    ]
}
```

#### 2. Receive the response and check if it has called one or more tools to execute it in the system ,And execute the tool called by the model.
The deepseek api responds to the system and requests the execution of the tool responsible for fetching the weather status.
```php

$response = $client->run();

$response = json_decode($response, true);
$message = $response['choices'][0]['message'];
$firstFunction = $message['tool_calls'][0];
if ($firstFunction['function']['name'] == "get_weather")
{
    $weather_data = get_weather($firstFunction['function']['arguments']['city']);
}

```

#### 3. Coordinate the results and send the previous response with the results of the executed tools.
Formats the response, and sends it back to the form.
```php
$response2 = $client->queryToolCall(
        $message['tool_calls'],
        $message['content'],
        $message['role']
    )->queryTool(
        $firstFunction['id'],
        $weather_data
);
```

Request like
```json 
{
    "messages": [
        {
            "role": "user",
            "content": "What is the weather like in Cairo?"
        },
        {
            "content": "What is the weather like in Cairo?",
            "tool_calls": [
                {
                    "id": "930c60df-3ec75f81e00e",
                    "type": "function",
                    "function": {
                        "name": "get_weather",
                        "arguments": {
                            "city": "Cairo"
                        }
                    }
                }
            ],
            "role": "assistant"
        },
        {
            "role": "tool",
            "tool_call_id": "930c60df-3ec75f81e00e",
            "content": "{\"temperature\":22,\"condition\":\"Sunny\"}"
        }
    ],
    "model": "deepseek-chat",
    "stream": false,
    "temperature": 1.3,
    "tools": [
        {
            "type": "function",
            "function": {
                "name": "get_weather",
                "description": "Get the current weather in a given city",
                "parameters": {
                    "type": "object",
                    "properties": {
                        "city": {
                            "type": "string",
                            "description": "The city name"
                        }
                    },
                    "required": [
                        "city"
                    ]
                }
            }
        }
    ]
}
```

#### 4. Receive the final response from the model and pass it to the end user.
The deepseek api responds with the final response, which is the weather status according to the data passed to it in the example.
```php

$response2 = $response2->run();
echo $response2;
```
Output response like :-
```json
{
    "id": "chat_67890",
    "object": "chat.completion",
    "created": 1677654322,
    "model": "deepseek-chat",
    "choices": [
        {
            "index": 0,
            "message": {
                "role": "assistant",
                "content": "The weather in Cairo is 22℃."
            },
            "finish_reason": "stop"
        }
    ]
}
```

