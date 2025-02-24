<?php

namespace DeepSeek\Factories;

use DeepSeek\Contracts\Factories\ApiFactoryContract;
use DeepSeek\Enums\Configs\DefaultConfigs;
use DeepSeek\Enums\Requests\ClientTypes;
use DeepSeek\Enums\Requests\HeaderFlags;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use RuntimeException;
use InvalidArgumentException;

final class ApiFactory implements ApiFactoryContract
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;
    protected array $clientConfig;

    public static function build(): self
    {
        return new self();
    }

    public function setBaseUri(?string $baseUrl = null): self
    {
        $this->baseUrl = $baseUrl ? trim($baseUrl) : DefaultConfigs::BASE_URL->value;
        return $this;
    }

    public function setKey(string $apiKey): self
    {
        $this->apiKey = trim($apiKey);
        return $this;
    }

    public function setTimeout(?int $timeout = null): self
    {
        $this->timeout = $timeout ?: (int)DefaultConfigs::TIMEOUT->value;
        return $this;
    }

    public function initialize(): self
    {
        if (!isset($this->baseUrl)) {
            $this->setBaseUri();
        }

        if (!isset($this->apiKey)) {
            throw new RuntimeException('API key must be set using setKey() before initialization.');
        }

        if (!isset($this->timeout)) {
            $this->setTimeout();
        }

        $this->clientConfig = [
            HeaderFlags::BASE_URL->value => $this->baseUrl,
            HeaderFlags::TIMEOUT->value  => $this->timeout,
            HeaderFlags::HEADERS->value  => [
                HeaderFlags::AUTHORIZATION->value => 'Bearer ' . $this->apiKey,
                HeaderFlags::CONTENT_TYPE->value  => 'application/json',
            ],
        ];

        return $this;
    }

    public function run(?string $clientType = null): ClientInterface
    {
        $clientType = $clientType ?? ClientTypes::GUZZLE->value;

        if (!isset($this->clientConfig)) {
            $this->initialize();
        }

        return match (strtolower($clientType)) {
            ClientTypes::GUZZLE->value => new Client($this->clientConfig),
            ClientTypes::SYMFONY->value => new Psr18Client(HttpClient::create($this->clientConfig)),
            default => throw new InvalidArgumentException("Unsupported client type: {$clientType}")
        };
    }
}
