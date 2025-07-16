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
}
