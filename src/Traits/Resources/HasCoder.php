<?php

namespace DeepSeek\Traits\Resources;

use DeepSeek\Enums\Requests\QueryFlags;
use DeepSeek\Resources\Coder;

trait HasCoder
{
    /**
     * Send the accumulated queries to the code resource.
     *
     * Since 2.1.0, this shortcut honors the same configuration as run():
     * temperature, max_tokens, tools, and response_format set via the
     * corresponding setters are now included in the request body.
     */
    public function code(): string
    {
        $requestData = [
            QueryFlags::MESSAGES->value => $this->queries,
            QueryFlags::MODEL->value => $this->model,
            QueryFlags::STREAM->value => $this->stream,
            QueryFlags::TEMPERATURE->value => $this->temperature,
            QueryFlags::MAX_TOKENS->value => $this->maxTokens,
            QueryFlags::TOOLS->value => $this->tools,
            QueryFlags::RESPONSE_FORMAT->value => [
                'type' => $this->responseFormatType,
            ],
        ];
        $this->queries = [];
        $this->setResult((new Coder($this->httpClient))->sendRequest($requestData));

        return $this->getResult()->getContent();
    }
}
