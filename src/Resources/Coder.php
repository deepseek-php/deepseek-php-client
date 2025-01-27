<?php

declare(strict_types=1);

namespace DeepSeekPhp\Resources;

use DeepSeekPhp\Enums\Models;

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
        return Models::CODER->value;
    }
}
