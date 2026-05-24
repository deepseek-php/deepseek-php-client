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
    "model": "deepseek-v4-pro",
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
    "model": "deepseek-v4-pro",
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
    "model": "deepseek-v4-pro",
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

---

### Thinking-mode caveat

When using V4 models with thinking mode enabled (or the legacy `DeepSeek-R1`), assistant responses include a `reasoning_content` field at the same level as `content`. **This field MUST be echoed back on the next tool turn**; otherwise the DeepSeek API returns HTTP 400.

Since `v2.2.0` you can toggle thinking mode and reasoning effort directly:

```php
use DeepSeek\Enums\Configs\ReasoningEffort;
use DeepSeek\Enums\Configs\ThinkingType;

$client
    ->setThinking(['type' => ThinkingType::ENABLED->value])
    ->setReasoningEffort(ReasoningEffort::MAX->value);
```

The matching response-side helper for extracting `reasoning_content` and re-injecting it on the next turn is on the `v2.2.x` roadmap (see [CHANGELOG.md](../CHANGELOG.md)). In the meantime, callers should decode the response JSON and pass `reasoning_content` back manually inside the assistant message.

See the [DeepSeek reasoning model docs](https://api-docs.deepseek.com/guides/reasoning_model) for the full caveat list — notably that `temperature` / `top_p` are silently ignored in thinking mode and `logprobs` / `top_logprobs` return HTTP 400.

---

### Tool choice control (v2.2.0)

By default DeepSeek decides freely whether to call a tool. Use [`setToolChoice()`](../src/Traits/Client/HasGenerationParams.php) to constrain that behavior:

| Mode | Effect |
|---|---|
| `'none'` | The model will not call any tool. |
| `'auto'` | The model decides (default behavior). |
| `'required'` | The model MUST call at least one tool. |
| `['type' => 'function', 'function' => ['name' => 'foo']]` | The model MUST call the named function. |

```php
use DeepSeek\Enums\Queries\ToolChoiceMode;

// Force the model to call a tool (any tool)
$client->setTools($tools)
       ->setToolChoice(ToolChoiceMode::REQUIRED->value);

// Force the model to call get_weather specifically
$client->setTools($tools)
       ->setToolChoice([
           'type' => 'function',
           'function' => ['name' => 'get_weather'],
       ]);
```

---

### Strict mode tools (v2.2.0)

`strict: true` on a function definition guarantees the model produces arguments that conform exactly to the JSON schema. Use [`setStrictTool()`](../src/Traits/Client/HasToolsFunctionCalling.php) instead of building the array yourself:

```php
$client
    ->setStrictTool(
        name: 'get_weather',
        parameters: [
            'type' => 'object',
            'properties' => [
                'city' => ['type' => 'string', 'description' => 'The city name'],
            ],
            'required' => ['city'],
        ],
        description: 'Get the current weather in a given city',
    )
    ->query('What is the weather like in Cairo?');
```

`setStrictTool()` **appends** to whatever `setTools()` already set; it never replaces. You can mix strict and non-strict tools in the same request by chaining calls.
