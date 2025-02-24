<?php

namespace DeepSeek\Enums\Queries;

enum QueryRoles: string
{
    case USER = 'user';
    case SYSTEM = 'system';
    case ASSISTANT = 'assistant';
    case TOOL = 'tool';
}
