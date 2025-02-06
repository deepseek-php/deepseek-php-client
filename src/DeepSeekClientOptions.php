<?php

namespace DeepSeek;

use DeepSeek\Enums\Configs\DefaultConfigs;

class DeepSeekClientOptions
{
    public function __construct()
    {
        $this->timeout = (int)DefaultConfigs::TIMEOUT->value;
    }

    public int $timeout;

    public string $apiKey;
}