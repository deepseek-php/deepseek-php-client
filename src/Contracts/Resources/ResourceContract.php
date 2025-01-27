<?php

namespace DeepSeekPhp\Contracts\Resources;

/**
 * Interface for defining the structure of resource classes.
 */
interface ResourceContract
{
    /**
     * Get the endpoint suffix for the resource.
     *
     * @return string
     */
    public function getEndpointSuffix(): string;

    /**
     * Get the model associated with the resource.
     *
     * @return string
     */
    public function getDefaultModel(): string;

    /**
     * check if stream enabled or not.
     *
     * @return bool
     */
    public function getDefaultStream(): bool;
}
