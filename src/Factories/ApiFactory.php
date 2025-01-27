<?php

namespace DeepSeekPhp\Factories;

use DeepSeekPhp\Contracts\Factories\ApiFactoryContract;
use DeepSeekPhp\Enums\Configs\DefaultConfigs;
use DeepSeekPhp\Enums\Requests\HeaderFlags;
use GuzzleHttp\Client;

final class ApiFactory implements ApiFactoryContract
{
    /**
     * The API key for authentication.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The base URL for the API.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * The timeout value for the API request in seconds.
     *
     * @var int
     */
    protected int $timeout;

    /**
     * Returns an instance of the ApiFactory.
     *
     * This is a static factory method that creates a new instance of the class.
     *
     * @return self A new instance of the ApiFactory.
     */
    public static function build(): self
    {
        return new self;
    }

    /**
     * Set the base URL for the API.
     *
     * If no URL is provided, the default base URL from the configuration is used.
     *
     * @param string|null $baseUrl The base URL to set (optional).
     * @return self The instance of the self for method chaining.
     */
    public function setBaseUri(?string $baseUrl = null): self
    {
        $this->baseUrl = $baseUrl ? trim($baseUrl) : DefaultConfigs::BASE_URL->value;
        return $this;
    }

    /**
     * Set the API key for authentication.
     *
     * @param string $apiKey The API key to set.
     * @return self The instance of the self for method chaining.
     */
    public function setKey(string $apiKey): self
    {
        $this->apiKey = trim($apiKey);
        return $this;
    }

    /**
     * Set the timeout for the API request.
     *
     * If no timeout is provided, the default timeout value from the configuration is used.
     *
     * @param int|null $timeout The timeout value in seconds (optional).
     * @return self The instance of the self for method chaining.
     */
    public function setTimeout(?int $timeout = null): self
    {
        $this->timeout = $timeout ?: (int)DefaultConfigs::TIMEOUT->value;
        return $this;
    }

    /**
     * Build and return the Guzzle Client instance.
     *
     * This method creates and configures a new Guzzle HTTP client instance
     * using the provided base URL, timeout, and headers.
     *
     * @return Client A Guzzle client instance configured for the API.
     */
    public function run(): Client
    {
        $clientConfig = [
            HeaderFlags::BASE_URL->value => $this->baseUrl,
            HeaderFlags::TIMEOUT->value  => $this->timeout,
            HeaderFlags::HEADERS->value  => [
                HeaderFlags::AUTHORIZATION->value => 'Bearer ' . $this->apiKey,
                HeaderFlags::CONTENT_TYPE->value => "application/json",
            ],
        ];

        return new Client($clientConfig);
    }
}
