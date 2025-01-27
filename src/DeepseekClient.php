<?php

namespace DeepseekPhp;

use DeepSeekPhp\Contracts\DeepseekClientContract;
use DeepSeekPhp\Contracts\Models\ResultContract;
use DeepSeekPhp\Resources\Resource;
use Psr\Http\Client\ClientInterface;
use DeepSeekPhp\Factories\ApiFactory;
use DeepSeekPhp\Enums\Queries\QueryRoles;
use DeepSeekPhp\Enums\Requests\QueryFlags;
use DeepSeekPhp\Enums\Requests\HeaderFlags;
use DeepSeekPhp\Enums\Configs\TemperatureValues;
use DeepSeekPhp\Traits\Resources\{HasChat, HasCoder};

class DeepseekClient implements DeepseekClientContract
{
    use HasChat, HasCoder;

    /**
     * PSR-18 HTTP client for making requests.
     *
     * @var ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * Array to store accumulated queries.
     *
     * @var array
     */
    protected array $queries = [];

    /**
     * The model being used for API requests.
     *
     * @var string|null
     */
    protected ?string $model;

    /**
     * Indicates whether to enable streaming for API responses.
     *
     * @var bool
     */
    protected bool $stream;

    protected float $temperature;

    /**
     * response result contract
     * @var ResultContract
     */
    protected ResultContract $result;

    /**
     * Initialize the DeepseekClient with a PSR-compliant HTTP client.
     *
     * @param ClientInterface $httpClient The HTTP client used for making API requests.
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->model = null;
        $this->stream = false;
        $this->temperature = (float) TemperatureValues::GENERAL_CONVERSATION->value;
    }

    public function run(): string
    {
        $requestData = [
            QueryFlags::MESSAGES->value => $this->queries,
            QueryFlags::MODEL->value    => $this->model,
            QueryFlags::STREAM->value   => $this->stream,
            QueryFlags::TEMPERATURE->value   => $this->temperature,
        ];
        // Clear queries after sending
        $this->queries = [];
        $this->result = (new Resource($this->httpClient))->sendRequest($requestData);
        return $this->getResult()->getContent();
    }

    /**
     * Create a new DeepseekClient instance with the given API key.
     *
     * @param string $apiKey The API key for authentication.
     * @param string|null $baseUrl The base URL for the API (optional).
     * @param int|null $timeout The timeout duration for requests in seconds (optional).
     * @return self A new instance of the DeepseekClient.
     */
    public static function build(string $apiKey, ?string $baseUrl = null, ?int $timeout = null): self
    {
        $httpClient = ApiFactory::build()
            ->setBaseUri($baseUrl)
            ->setTimeout($timeout)
            ->setKey($apiKey)
            ->run();

        return new self($httpClient);
    }

    /**
     * Add a query to the accumulated queries list.
     *
     * @param string $content
     * @param string|null $role
     * @return self The current instance for method chaining.
     */
    public function query(string $content, ?string $role = null): self
    {
        $this->queries[] = $this->buildQuery($content, $role);
        return $this;
    }

    /**
     * Set the model to be used for API requests.
     *
     * @param string|null $model The model name (optional).
     * @return self The current instance for method chaining.
     */
    public function withModel(?string $model = null): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Enable or disable streaming for API responses.
     *
     * @param bool $stream Whether to enable streaming (default: true).
     * @return self The current instance for method chaining.
     */
    public function withStream(bool $stream = true): self
    {
        $this->stream = $stream;
        return $this;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    protected function buildQuery(string $content, ?string $role = null): array
    {
        return [
            'role' => $role ?: QueryRoles::USER->value,
            'content' => $content
        ];
    }

    /**
     * response result model
     * @return \DeepseekPhp\Contracts\Models\ResultContract
     */
    public function getResult(): ResultContract
    {
        return $this->result;
    }
}
