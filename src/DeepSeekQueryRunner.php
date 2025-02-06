<?php

namespace DeepSeek;

use DeepSeek\Contracts\Models\ResultContract;
use DeepSeek\Enums\Requests\QueryFlags;
use DeepSeek\Http\HttpClient;
use DeepSeek\Resources\Resource;

class DeepSeekQueryRunner implements IDeepSeekQueryRunner
{
    private HttpClient $httpClient;
    private DeepSeekQueryOptions $queryOptions;

    public function __construct(HttpClient $httpClient, DeepSeekQueryOptions $queryOptions)
    {
        $this->httpClient = $httpClient;
        $this->queryOptions = $queryOptions;
    }

    public function run(array $query): ResultContract
    {
        $requestData = [
            QueryFlags::MESSAGES->value => $query,
            QueryFlags::MODEL->value => $this->queryOptions->model,
            QueryFlags::STREAM->value => $this->queryOptions->stream,
            QueryFlags::TEMPERATURE->value => $this->queryOptions->temperature
        ];

        return (new Resource($this->httpClient->getInternalClient()))->sendRequest($requestData);
    }
}