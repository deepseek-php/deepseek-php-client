<?php

namespace DeepSeekPhp\Enums\Configs;

enum DefaultConfigs: string
{
    case BASE_URL = 'https://api.deepseek.com/v3';
    case MODEL = 'DeepSeek-R1';
    case TIMEOUT = '30';
    case STREAM = 'false';
}
