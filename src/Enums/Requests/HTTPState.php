<?php

namespace DeepSeekPhp\Enums\Requests;

enum HTTPState: int
{
    /**
     * HTTP successful response
     * status is ok
     */
    case OK = 200;

    /**
     * HTTP client error response
     * status is unauthorized
     */
    case UNAUTHORIZED = 401;

    /**
     * HTTP client error response
     * status is payment required
     */
    case PAYMENT_REQUIRED = 402;

}
