<?php

namespace DeepSeekPhp\Traits\Resources;

use DeepSeekPhp\Resources\Chat;

trait HasChat
{
    /**
     * Send the accumulated queries to the Chat resource.
     *
     * @return string
     */
    public function chat(): string
    {
        $requestData = [
            'messages' => $this->queries,
            'model' => $this->model,
            'stream' => $this->stream,
        ];
        $this->queries = [];
        return (new Chat($this->httpClient))->sendRequest($requestData);
    }
}
