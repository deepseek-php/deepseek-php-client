<?php

namespace DeepSeek;

use DeepSeek\Contracts\ClientContract;
use DeepSeek\Contracts\Models\ResultContract;
use DeepSeek\Enums\Requests\ClientTypes;
use DeepSeek\Enums\Requests\EndpointSuffixes;
use DeepSeek\Resources\Resource;
use Psr\Http\Client\ClientInterface;
use DeepSeek\Factories\ApiFactory;
use DeepSeek\Enums\Queries\QueryRoles;
use DeepSeek\Enums\Requests\QueryFlags;
use DeepSeek\Enums\Configs\TemperatureValues;
use DeepSeek\Traits\Resources\{HasChat, HasCoder};
use DeepSeek\Traits\Client\HasToolsFunctionCalling;

class DeepSeekClient implements ClientContract
{
    use HasChat, HasCoder;
    use HasToolsFunctionCalling;

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
    protected int $maxTokens;
    protected string $responseFormatType;

    /**
     * response result contract
     * @var ResultContract
     */
    protected ResultContract $result;

    protected string $requestMethod;

    protected ?string $endpointSuffixes;

    /**
     * Array of tools for using function calling.
     * @var array|null $tools
     */
    protected ?array $tools;

    /**
     * Initialize the DeepSeekClient with a PSR-compliant HTTP client.
     *
     * @param ClientInterface $httpClient The HTTP client used for making API requests.
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->model = null;
        $this->stream = false;
        $this->requestMethod = 'POST';
        $this->endpointSuffixes = EndpointSuffixes::CHAT->value;
        $this->temperature = (float) TemperatureValues::GENERAL_CONVERSATION->value;
        $this->maxTokens = (int) TemperatureValues::MAX_TOKENS->value;
        $this->responseFormatType = TemperatureValues::RESPONSE_FORMAT_TYPE->value;
        $this->tools = null;
    }

    public function run(): string
    {
        $requestData = [
            QueryFlags::MESSAGES->value => $this->queries,
            QueryFlags::MODEL->value    => $this->model,
            QueryFlags::STREAM->value   => $this->stream,
            QueryFlags::TEMPERATURE->value   => $this->temperature,
            QueryFlags::MAX_TOKENS->value   => $this->maxTokens,
            QueryFlags::TOOLS->value  => $this->tools,
            QueryFlags::RESPONSE_FORMAT->value  => [
                'type' => $this->responseFormatType
            ],
        ];

        $this->setResult((new Resource($this->httpClient, $this->endpointSuffixes))->sendRequest($requestData, $this->requestMethod));
        return $this->getResult()->getContent();
    }

    /**
     * Create a new DeepSeekClient instance with the given API key.
     *
     * @param string $apiKey The API key for authentication.
     * @param string|null $baseUrl The base URL for the API (optional).
     * @param int|null $timeout The timeout duration for requests in seconds (optional).
     * @return self A new instance of the DeepSeekClient.
     */
    public static function build(string $apiKey, ?string $baseUrl = null, ?int $timeout = null, ?string $clientType = null): self
    {
        $clientType = $clientType ?? ClientTypes::GUZZLE->value;

        $httpClient = ApiFactory::build()
            ->setBaseUri($baseUrl)
            ->setTimeout($timeout)
            ->setKey($apiKey)
            ->run($clientType);

        return new self($httpClient);
    }

    /**
     * Add a query to the accumulated queries list.
     *
     * @param string $content
     * @param string|null $role
     * @return self The current instance for method chaining.
     */
    public function query(string $content, ?string $role = "user"): self
    {
        $this->queries[] = $this->buildQuery($content, $role);
        return $this;
    }
    
    /**
     * Reset a queries list to empty.
     *
     * @return self The current instance for method chaining.
     */
    public function resetQueries()
    {
        $this->queries = [];
        return $this;
    }

    /**
     * get list of available models .
     *
     * @return self The current instance for method chaining.
     */
    public function getModelsList(): self
    {
        $this->endpointSuffixes = EndpointSuffixes::MODELS_LIST->value;
        $this->requestMethod = 'GET';
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

    public function setMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;
        return $this;
    }

    public function setResponseFormat(string $type): self
    {
        $this->responseFormatType = $type;
        return $this;
    }

    public function buildQuery(string $content, ?string $role = null): array
    {
        return [
            'role' => $role ?: QueryRoles::USER->value,
            'content' => $content
        ];
    }

    /**
     * set result model
     * @param \DeepSeek\Contracts\Models\ResultContract $result
     * @return self The current instance for method chaining.
     */
    public function setResult(ResultContract $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * response result model
     * @return \DeepSeek\Contracts\Models\ResultContract
     */
    public function getResult(): ResultContract
    {
        return $this->result;
    }
}
