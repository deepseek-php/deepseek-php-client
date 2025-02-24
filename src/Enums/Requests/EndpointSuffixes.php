<?php

namespace DeepSeek\Enums\Requests;

enum EndpointSuffixes: string
{
    case CHAT = '/chat/completions';
    case MODELS_LIST = '/models';
}
