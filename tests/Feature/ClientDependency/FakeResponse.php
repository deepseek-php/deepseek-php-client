<?php

namespace Tests\Feature\ClientDependency;

class FakeResponse 
{
    public function toolFunctionCalling()
    {
        return <<<responseAPI
            {
                "id": "930c60df-bf64-41c9-a88e-3ec75f81e00e",
                "choices": [
                    {
                        "finish_reason": "stop",
                        "index": 0,
                        "message": {
                            "content": "What is the weather like in Cairo?",
                            "tool_calls": [
                                {
                                    "id": "930c60df-3ec75f81e00e",
                                    "type": "function",
                                    "function": {
                                        "name": "get_weather",
                                        "arguments": {"city": "Cairo"}
                                    }
                                }
                            ],
                            "role": "assistant"
                        }
                    }
                ],
                "created": 1705651092,
                "model": "deepseek-chat",
                "object": "chat.completion",
                "usage": {
                    "completion_tokens": 10,
                    "prompt_tokens": 16,
                    "total_tokens": 26
                }
            }
        responseAPI;
    }
    public function resultToolFunctionCalling()
    {
        return <<<responseAPI
            {
                "id": "930c60df-bf64-41c9-a88e-3ec75f81e00e",
                "choices": [
                    {
                        "finish_reason": "stop",
                        "index": 0,
                        "message": {
                            "content": "The weather in Cairo is sunny with a temperature of 22 degrees.",
                            "role": "assistant"
                        }
                    }
                ],
                "created": 1705651092,
                "model": "deepseek-chat",
                "object": "chat.completion",
                "usage": {
                    "completion_tokens": 10,
                    "prompt_tokens": 16,
                    "total_tokens": 26
                }
            }
        responseAPI;
    }
}