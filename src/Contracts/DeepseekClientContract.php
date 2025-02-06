<?php

namespace DeepSeek\Contracts;

use DeepSeek\DeepSeekClientOptions;

interface DeepseekClientContract
{
    public static function build(DeepSeekClientOptions $options): self;
    public function run(): string;
    public function query(string $content, ?string $role = "user"): self;
    public function withModel(?string $model = null): self;
    public function withStream(bool $stream = true): self;
}
