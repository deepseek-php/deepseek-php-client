<?php

namespace DeepSeekPhp\Enums\Requests;

enum HeaderFlags: string
{
    case BASE_URL = 'base_uri';
    case TIMEOUT = 'timeout';
    case HEADERS = 'headers';
    case AUTHORIZATION = 'Authorization';
    case CONTENT_TYPE = 'content-type';
}
