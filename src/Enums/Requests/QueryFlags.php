<?php

namespace DeepSeek\Enums\Requests;

enum QueryFlags: string
{
    case MESSAGES = 'messages';
    case MODEL = 'model';
    case STREAM = 'stream';
    case TEMPERATURE = 'temperature';
    case MAX_TOKENS = 'max_tokens';
    case TOOLS = 'tools';
    case RESPONSE_FORMAT = 'response_format';

    /**
     * Up to 16 stop sequences (string or string[]).
     *
     * @see https://api-docs.deepseek.com/api/create-chat-completion
     */
    case STOP = 'stop';

    /**
     * Nucleus sampling parameter (0..1). Alternative to temperature.
     *
     * @see https://api-docs.deepseek.com/api/create-chat-completion
     */
    case TOP_P = 'top_p';

    /**
     * Tool choice control: "none" | "auto" | "required" | {"type": "function", "function": {"name": "..."}}.
     *
     * @see https://api-docs.deepseek.com/api/create-chat-completion
     */
    case TOOL_CHOICE = 'tool_choice';

    /**
     * Whether to return log probabilities of the output tokens.
     *
     * @see https://api-docs.deepseek.com/api/create-chat-completion
     */
    case LOGPROBS = 'logprobs';

    /**
     * Number of most likely tokens to return at each token position with log probabilities.
     *
     * @see https://api-docs.deepseek.com/api/create-chat-completion
     */
    case TOP_LOGPROBS = 'top_logprobs';

    /**
     * End-user identifier for rate-limit isolation, content safety, and KV-cache isolation.
     *
     * @see https://api-docs.deepseek.com/quick_start/rate_limit
     */
    case USER = 'user';

    /**
     * Thinking mode configuration: ["type" => "enabled" | "disabled"].
     *
     * @see https://api-docs.deepseek.com/guides/reasoning_model
     */
    case THINKING = 'thinking';

    /**
     * Reasoning effort: "high" | "max".
     *
     * @see https://api-docs.deepseek.com/guides/reasoning_model
     */
    case REASONING_EFFORT = 'reasoning_effort';
}
