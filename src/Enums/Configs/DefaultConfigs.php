<?php

namespace DeepSeek\Enums\Configs;

enum DefaultConfigs: string
{
    case BASE_URL = 'https://api.deepseek.com';
    case MODEL = 'DeepSeek-R1';
    case TIMEOUT = '30';
    case STREAM = 'false';
}
