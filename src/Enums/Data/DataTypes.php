<?php

namespace DeepSeekPhp\Enums\Data;

enum DataTypes: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case ARRAY = 'array';
    case OBJECT = 'object';
    case BOOL = 'bool';
    case JSON = 'json';
}
