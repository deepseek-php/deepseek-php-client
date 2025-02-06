<?php

namespace DeepSeek\Http;

final class HttpClientFactory
{
    public static function Create(string $baseAddress) : HttpClient {
        $client = new HttpClient();
        $client->baseAddress = $baseAddress;

        return $client;
    }
}