<?php

namespace DeepSeek\Enums\Configs;

enum DefaultConfigs: string
{
    case BASE_URL = 'https://api.deepseek.com';
    case MODEL = 'deepseek-v4-flash';
    case TIMEOUT = '30';
    case STREAM = 'false';
}
