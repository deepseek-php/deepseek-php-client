<?php

namespace DeepSeek;

use DeepSeek\Contracts\Models\ResultContract;

interface IDeepSeekQuery
{
    public function query(string $content, ?string $role = "user"): IDeepSeekQuery;

    public function withModel(string $model): IDeepSeekQuery;

    public function withStream(bool $stream): IDeepSeekQuery;

    public function withTemperature(float $temperature): IDeepSeekQuery;

    public function getQueryOptions(): DeepSeekQueryOptions;

    public function run(): ResultContract;
}