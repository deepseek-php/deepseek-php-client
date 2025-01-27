<?php

namespace DeepSeekPhp\Enums\Requests;

enum QueryFlags: string
{
    case MESSAGES = 'messages';
    case MODEL = 'model';
    case STREAM = 'stream';
    case TEMPERATURE = 'temperature';
}
