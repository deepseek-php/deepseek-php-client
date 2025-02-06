<?php

namespace DeepSeek\Http;

use DeepSeek\Enums\Requests\HeaderFlags;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use function PHPUnit\Framework\isNull;

class HttpClient
{
    private ?ClientInterface $client = null;

    function __construct()
    {
        $this->headers = [];
    }

    public string $baseAddress;

    public int $timeout;

    public array $headers;

    public function getInternalClient() : ClientInterface {
        if (isNull($this->client)) {
            $clientConfig = [
                HeaderFlags::BASE_URL->value => $this->baseAddress,
                HeaderFlags::TIMEOUT->value  => $this->timeout,
                HeaderFlags::HEADERS->value  => $this->headers
            ];

            $this->client = new Client($clientConfig);
        }

        return $this->client;
    }
}