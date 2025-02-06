<?php

namespace DeepSeek;

use DeepSeek\Http\HttpClient;

interface IDeepSeekClient
{
    public function getClientOptions(): DeepSeekClientOptions;

    public function getHttpClient(): HttpClient;
}