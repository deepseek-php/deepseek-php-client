<?php

namespace DeepSeek\Contracts\Resources;

/**
 * Interface for defining the structure of resource classes.
 */
interface ResourceContract
{
    /**
     * Get the endpoint suffix for the resource.
     */
    public function getEndpointSuffix(): string;

    /**
     * Get the model associated with the resource.
     */
    public function getDefaultModel(): string;

    /**
     * check if stream enabled or not.
     */
    public function getDefaultStream(): bool;
}
