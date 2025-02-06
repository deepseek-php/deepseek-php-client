<?php

namespace DeepSeek;

use DeepSeek\Contracts\Models\ResultContract;

interface IDeepSeekQueryRunner
{
    public function run(array $query): ResultContract;
}