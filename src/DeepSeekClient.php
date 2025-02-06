<?php

namespace DeepSeek;

use DeepSeek\Contracts\Models\ResultContract;
use DeepSeek\Enums\Configs\DefaultConfigs;
use DeepSeek\Enums\Requests\HeaderFlags;
use DeepSeek\Http\DefaultHttpClientFactory;
use DeepSeek\Http\HttpClient;
use DeepSeek\Traits\Resources\{HasChat, HasCoder};

class DeepSeekClient implements IDeepSeekClient, IDeepSeekQuery
{
    use HasChat, HasCoder;

    private HttpClient $httpClient;
    private DeepSeekClientOptions $clientOptions;
    private DeepSeekQueryOptions $queryOptions;
    private array $query = [];

    public function __construct(HttpClient $httpClient, ?DeepSeekClientOptions $options)
    {
        $this->httpClient = $httpClient;
        $this->clientOptions = $options ?? new DeepSeekClientOptions();
        $this->queryOptions = new DeepSeekQueryOptions();
    }

    /**
     * Create a new DeepSeekClient instance with a given options.
     *
     * @param DeepSeekClientOptions $options A client options.
     * @return self A new instance of the DeepSeekClient.
     */
    public static function build(DeepSeekClientOptions $options): self
    {
        $httpClient = DefaultHttpClientFactory::getInstance()->createClient("DeepSeekClient");

        $httpClient->baseAddress = DefaultConfigs::BASE_URL->value;
        $httpClient->timeout = $options->timeout ?? (int)DefaultConfigs::TIMEOUT->value;
        $httpClient->headers = [
            HeaderFlags::AUTHORIZATION->value => 'Bearer ' . $options->apiKey,
            HeaderFlags::CONTENT_TYPE->value => "application/json",
        ];

        return new self($httpClient, $options);
    }

    /**
     * Add a query to the accumulated queries list.
     *
     * @param string $content
     * @param string|null $role
     * @return self The current instance for method chaining.
     */
    public function query(string $content, ?string $role = "user"): IDeepSeekQuery
    {
        $this->query[] = [
            'role' => $role,
            'content' => $content
        ];

        return $this;
    }

    /**
     * Set the model to be used for API requests.
     *
     * @param string $model The model name (optional).
     * @return self The current instance for method chaining.
     */
    public function withModel(string $model): IDeepSeekQuery
    {
        $this->queryOptions->model = $model;

        return $this;
    }

    /**
     * Enable or disable streaming for API responses.
     *
     * @param bool $stream Whether to enable streaming.
     * @return self The current instance for method chaining.
     */
    public function withStream(bool $stream): IDeepSeekQuery
    {
        $this->queryOptions->stream = $stream;

        return $this;
    }

    public function withTemperature(float $temperature): IDeepSeekQuery
    {
        $this->queryOptions->temperature = $temperature;

        return $this;
    }

    public function run(): ResultContract
    {
        $queryRunner = new DeepSeekQueryRunner($this->httpClient, $this->getQueryOptions());
        $result = $queryRunner->run($this->query);

        // Clear queries after sending
        $this->query = [];

        return $result;
    }

    public function getClientOptions(): DeepSeekClientOptions
    {
        return $this->clientOptions;
    }

    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    public function getQueryOptions(): DeepSeekQueryOptions
    {
        return $this->queryOptions;
    }
}
