<?php

declare(strict_types=1);

namespace DeepSeek\Resources;

use DeepSeek\Contracts\Models\ResultContract;
use DeepSeek\Contracts\Resources\ResourceContract;
use DeepSeek\Enums\Configs\DefaultConfigs;
use DeepSeek\Enums\Models;
use DeepSeek\Enums\Data\DataTypes;
use DeepSeek\Enums\Requests\EndpointSuffixes;
use DeepSeek\Enums\Requests\QueryFlags;
use DeepSeek\Models\BadResult;
use DeepSeek\Models\FailureResult;
use DeepSeek\Models\SuccessResult;
use DeepSeek\Traits\Queries\HasQueryParams;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

class Resource implements ResourceContract
{
    use HasQueryParams;

    protected ClientInterface $client;
    protected ?string $endpointSuffixes;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;

    public function __construct(
        ClientInterface $client,
        ?string $endpointSuffixes = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->client = $client;
        $this->endpointSuffixes = $endpointSuffixes ?: EndpointSuffixes::CHAT->value;
        $this->requestFactory = $requestFactory ?: new Psr17Factory();
        $this->streamFactory = $streamFactory ?: new Psr17Factory();
    }

    public function sendRequest(array $requestData, ?string $requestMethod = 'POST'): ResultContract
    {
        try {
            $request = $this->requestFactory->createRequest(
                $requestMethod,
                $this->getEndpointSuffix()
            );

            if ($requestMethod === 'POST') {
                $request = $request
                    ->withHeader('Content-Type', 'application/json')
                    ->withBody(
                        $this->streamFactory->createStream(
                            json_encode($this->resolveHeaders($requestData))
                        ));
            }

            $response = $this->client->sendRequest($request);

            return (new SuccessResult())->setResponse($response);
        } catch (BadResponseException $badResponse) {
            return (new BadResult())->setResponse($badResponse->getResponse());
        } catch (GuzzleException $error) {
            return new FailureResult($error->getCode(), $error->getMessage());
        } catch (\Exception $error) {
            return new FailureResult($error->getCode(), '{"error":"'.$error->getMessage().'"}');
        }
    }

    /**
     * Merge request data with default headers.
     *
     * This method merges the given query data with custom headers that are
     * prepared for the request.
     *
     * @param array $requestData The data to send in the request.
     * @return array The merged request data with default headers.
     */
    protected function resolveHeaders(array $requestData): array
    {
        return array_merge($requestData, $this->prepareCustomHeaderParams($requestData));
    }

    /**
     * Prepare the custom headers for the request.
     *
     * This method loops through the query parameters and applies the appropriate
     * type conversion before returning the final headers.
     *
     * @param array $query The data to send in the request.
     * @return array The custom headers for the request.
     */
    public function prepareCustomHeaderParams(array $query): array
    {
        $headers = [];
        $params = $this->getAllowedQueryParamsList();

        // Loop through the parameters and apply the conversion logic dynamically
        foreach ($params as $key => $type) {
            $headers[$key] = $this->getQueryParam($query, $key, $this->getDefaultForKey($key), $type);
        }

        return $headers;
    }

    /**
     * Get the endpoint suffix for the resource.
     *
     * This method returns the endpoint suffix that is used in the API URL.
     *
     * @return string The endpoint suffix.
     */
    public function getEndpointSuffix(): string
    {
        return $this->endpointSuffixes;
    }

    /**
     * Get the model associated with the resource.
     *
     * This method returns the default model value associated with the resource.
     *
     * @return string The default model value.
     */
    public function getDefaultModel(): string
    {
        return Models::CHAT->value;
    }

    /**
     * Check if stream is enabled or not.
     *
     * This method checks whether the streaming option is enabled based on the
     * default configuration.
     *
     * @return bool True if streaming is enabled, false otherwise.
     */
    public function getDefaultStream(): bool
    {
        return DefaultConfigs::STREAM->value === 'true';
    }

    /**
     * Get the list of query parameters and their corresponding types for conversion.
     *
     * This method returns an array of query keys and their associated data types
     * for use in preparing the custom headers.
     *
     * @return array An associative array of query keys and their data types.
     */
    protected function getAllowedQueryParamsList(): array
    {
        return [
            QueryFlags::MODEL->value => DataTypes::STRING->value,
            QueryFlags::STREAM->value => DataTypes::BOOL->value,
        ];
    }
}
