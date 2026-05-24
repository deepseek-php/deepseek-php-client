<?php

namespace DeepSeek\Contracts\Factories;

use DeepSeek\Factories\ApiFactory;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

interface ApiFactoryContract
{
    /**
     * Create a new instance of the factory.
     */
    public static function build(): ApiFactory;

    /**
     * Set the base URL for the API.
     *
     * @param  string|null  $baseUrl  The base URL to set (optional).
     */
    public function setBaseUri(?string $baseUrl = null): ApiFactory;

    /**
     * Set the API key for authentication.
     *
     * @param  string  $apiKey  The API key to set.
     */
    public function setKey(string $apiKey): ApiFactory;

    /**
     * Set the timeout for the API request.
     *
     * @param  int|null  $timeout  The timeout value in seconds (optional).
     */
    public function setTimeout(?int $timeout = null): ApiFactory;

    /**
     * Build and return http Client instance.
     */
    public function run(?string $clientType = null): ClientInterface;
}
