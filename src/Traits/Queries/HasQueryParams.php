<?php

namespace DeepseekPhp\Traits\Queries;

use DeepseekPhp\Enums\Data\DataTypes;
use DeepseekPhp\Enums\Requests\QueryFlags;

trait HasQueryParams
{
    /**
     * Helper method to get the query parameter or default value with type conversion.
     *
     * @param array $query
     * @param string $key
     * @param mixed $default
     * @param string $type
     * @return mixed
     */
    private function getQueryParam(array $query, string $key, $default, string $type): mixed
    {
        if (isset($query[$key])) {
            $value = $query[$key];
            return $this->convertValue($value, $type);
        }

        return $default;
    }

    /**
     * Convert the value to the specified type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private function convertValue($value, string $type): mixed
    {
        return match ($type) {
            DataTypes::STRING->value => (string)$value,
            DataTypes::INTEGER->value => (int)$value,
            DataTypes::FLOAT->value => (float)$value,
            DataTypes::ARRAY->value => (array)$value,
            DataTypes::OBJECT->value => (object)$value,
            DataTypes::BOOL->value => (bool)$value,
            DataTypes::JSON->value => json_decode((string)$value, true),
            default => $value,
        };
    }

    /**
     * Get default value for specific query keys.
     *
     * @param string $key
     * @return mixed
     */
    private function getDefaultForKey(string $key): mixed
    {
        return match ($key) {
            QueryFlags::MODEL->value => $this->getDefaultModel(),
            QueryFlags::STREAM->value => $this->getDefaultStream(),
            default => null,
        };
    }
}
