<?php

declare(strict_types=1);

namespace DeepSeek\Resources;

use DeepSeek\Enums\Models;

class Coder extends Resource
{
    /**
     * Get the model associated with the resource.
     *
     * This method returns the default model value associated with the resource.
     *
     * @return string The default model value.
     */
    public function getDefaultModel(): string
    {
        return Models::V4_FLASH->value;
    }
}
