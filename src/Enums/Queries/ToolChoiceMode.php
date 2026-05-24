<?php

namespace DeepSeek\Enums\Queries;

/**
 * Pre-defined string modes for the OpenAI-style "tool_choice" parameter.
 *
 * Named tool choice (forcing a specific function) is expressed as an array:
 *   ['type' => 'function', 'function' => ['name' => 'my_function']]
 *
 * @see https://api-docs.deepseek.com/api/create-chat-completion
 */
enum ToolChoiceMode: string
{
    case NONE = 'none';
    case AUTO = 'auto';
    case REQUIRED = 'required';
}
