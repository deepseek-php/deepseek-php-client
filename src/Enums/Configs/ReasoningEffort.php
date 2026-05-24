<?php

namespace DeepSeek\Enums\Configs;

/**
 * Reasoning effort levels for thinking-capable models (DeepSeek V4).
 *
 * Per the DeepSeek reasoning model docs, "high" is the default for normal
 * requests and "max" is recommended for agent flows (e.g. Claude Code / OpenCode).
 *
 * @see https://api-docs.deepseek.com/guides/reasoning_model
 */
enum ReasoningEffort: string
{
    case HIGH = 'high';
    case MAX = 'max';
}
