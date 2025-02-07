<?php

namespace DeepSeek\Contracts;

interface DeepseekClientContract
{
    public static function build(string $apiKey): self;
    public function run(): string;
    public function query(
        ?string $content = null,
        ?string $role = "user",
        ?string $toolCallId = null,
        ?array $toolCalls = null,
    ): self;
    public function withModel(?string $model = null): self;
    public function withStream(bool $stream = true): self;
    public function setTemperature(float $temperature): self;
    public function setTools(array $tools): self;
}
