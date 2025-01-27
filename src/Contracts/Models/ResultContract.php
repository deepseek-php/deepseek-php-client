<?php

namespace DeepSeekPhp\Contracts\Models;


interface ResultContract
{
    /**
     * result status code 
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * result content date as a string
     * @return string
     */
    public function getContent(): string;

    /**
     * if response status code is ok (200)
     * @return bool
     */
    public function isSuccess(): bool;
}
