<?php

namespace DeepSeek\Traits\Resources;

use DeepSeek\Enums\Requests\QueryFlags;
use DeepSeek\Resources\Chat;

trait HasChat
{
    /**
     * Send the accumulated queries to the Chat resource.
     *
     * Since 2.1.0, this shortcut honors the same configuration as run():
     * temperature, max_tokens, tools, and response_format set via the
     * corresponding setters are now included in the request body.
     */
    public function chat(): string
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
        $this->setResult((new Chat($this->httpClient))->sendRequest($requestData));

        return $this->getResult()->getContent();
    }
}
