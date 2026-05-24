<?php

namespace DeepSeek\Enums\Configs;

/**
 * Thinking mode toggle for DeepSeek V4 models.
 *
 * The API field "thinking.type" defaults to "enabled" on the server side.
 * Set "disabled" to bypass the reasoning step.
 *
 * @see https://api-docs.deepseek.com/guides/reasoning_model
 */
enum ThinkingType: string
{
    case ENABLED = 'enabled';
    case DISABLED = 'disabled';
}
