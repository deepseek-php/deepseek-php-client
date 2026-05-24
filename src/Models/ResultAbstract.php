<?php

declare(strict_types=1);

namespace DeepSeek\Models;

use DeepSeek\Contracts\Models\ResultContract;
use DeepSeek\Enums\Requests\HTTPState;
use Psr\Http\Message\ResponseInterface;

abstract class ResultAbstract implements ResultContract
{
    protected ?int $statusCode;

    protected ?string $content;

    /**
     * handel response coming from request
     */
    protected ?ResponseInterface $response;

    public function __construct(?int $statusCode = null, ?string $content = null)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    protected function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setContent(string $content): void
    {
        // Strip DeepSeek Keep-Alive padding per
        // https://api-docs.deepseek.com/quick_start/rate_limit#request-keep-alive-mechanism
        // - Non-streaming responses: leading empty lines before the JSON body
        // - Streaming responses: ": keep-alive" SSE comment lines anywhere in the stream
        $content = preg_replace('/^: keep-alive\R/m', '', $content) ?? $content;
        $this->content = ltrim($content, "\r\n");
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setResponse(ResponseInterface $response): static
    {
        $this->response = $response;
        $this->setStatusCode($this->getResponse()->getStatusCode());
        $this->setContent($this->getResponse()->getBody()->getContents());

        return $this;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function isSuccess(): bool
    {
        return $this->getStatusCode() === HTTPState::OK->value;
    }
}
