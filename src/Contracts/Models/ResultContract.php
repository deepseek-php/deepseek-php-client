<?php

namespace DeepSeek\Contracts\Models;

interface ResultContract
{
    /**
     * result status code
     */
    public function getStatusCode(): int;

    /**
     * result content date as a string
     */
    public function getContent(): string;

    /**
     * if response status code is ok (200)
     */
    public function isSuccess(): bool;
}
