<?php

namespace DeepSeekPhp\Traits\Queries;

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
        switch ($type) {
            case DataTypes::STRING->value:
                return (string) $value;
            case DataTypes::INTEGER->value:
                return (int) $value;
            case DataTypes::FLOAT->value:
                return (float) $value;
            case DataTypes::ARRAY->value:
                return (array) $value;
            case DataTypes::OBJECT->value:
                return (object) $value;
            case DataTypes::BOOL->value:
                return (bool) $value;
            case DataTypes::JSON->value:
                return json_decode((string) $value, true);
            default:
                return $value;
        }
    }

    /**
     * Get default value for specific query keys.
     *
     * @param string $key
     * @return mixed
     */
    private function getDefaultForKey(string $key): mixed
    {
        switch ($key) {
            case QueryFlags::MODEL->value:
                return $this->getDefaultModel();
            case QueryFlags::STREAM->value:
                return $this->getDefaultStream();
            default:
                return null;
        }
    }
}
