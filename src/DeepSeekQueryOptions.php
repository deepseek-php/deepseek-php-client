<?php

namespace DeepSeek;

use DeepSeek\Enums\Configs\TemperatureValues;

class DeepSeekQueryOptions
{
    public function __construct()
    {
        $this->model = null;
        $this->stream = true;
        $this->temperature = (float)TemperatureValues::GENERAL_CONVERSATION->value;
    }

    public ?string $model;

    public bool $stream;

    public float $temperature;
}