<?php

declare(strict_types=1);

namespace DeepSeekPhp\Models;


use DeepSeekPhp\Contracts\Models\ResultContract;
use DeepSeekPhp\Enums\Requests\HTTPState;
use Psr\Http\Message\ResponseInterface;

abstract class ResultAbstract implements ResultContract
{
    protected ?int $statusCode;
    protected ?string $content;
    /**
     * handel response comeing from request
     * @var ResponseInterface|null
     */
    protected ?ResponseInterface $response;
    public function __construct(?int $statusCode = null, ?string $content = null)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }
    protected function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    protected function setContent(string $content): void
    {
        $this->content = $content;
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
        return ($this->getStatusCode() === HTTPState::OK->value);
    }
}

