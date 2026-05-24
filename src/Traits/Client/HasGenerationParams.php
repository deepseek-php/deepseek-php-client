<?php

namespace DeepSeek\Traits\Client;

use DeepSeek\Enums\Requests\QueryFlags;

/**
 * Holds the v2.2.0 additive generation parameter setters and their backing
 * nullable state. Every property defaults to null and is omitted from the
 * outgoing request body unless its corresponding setter has been called.
 *
 * This is the backward-compatibility contract: existing v2.1.x callers
 * that never invoke any of these setters will see a byte-identical request
 * payload compared to the previous release.
 */
trait HasGenerationParams
{
    /**
     * Up to 16 stop sequences. Always stored as an array of strings.
     */
    protected ?array $stop = null;

    /**
     * Nucleus sampling parameter in the [0, 1] range.
     */
    protected ?float $topP = null;

    /**
     * OpenAI-style tool_choice value. Either one of the string modes
     * ("none" | "auto" | "required") or a named function array shape.
     *
     * @var string|array<string,mixed>|null
     */
    protected string|array|null $toolChoice = null;

    /**
     * Whether the API should return log probabilities for output tokens.
     */
    protected ?bool $logprobs = null;

    /**
     * Number of most-likely tokens to include in the logprobs response.
     */
    protected ?int $topLogprobs = null;

    /**
     * End-user identifier. Sent as the OpenAI-spec "user" field.
     */
    protected ?string $userId = null;

    /**
     * Thinking-mode configuration array, e.g. ["type" => "enabled"].
     *
     * @var array<string,mixed>|null
     */
    protected ?array $thinking = null;

    /**
     * Reasoning effort level ("high" | "max").
     */
    protected ?string $reasoningEffort = null;

    /**
     * Set the stop sequences. Accepts a single string or an array of strings.
     *
     * @param  string|array<int,string>  $stop  Up to 16 stop sequences.
     * @return self The current instance for method chaining.
     */
    public function setStop(string|array $stop): self
    {
        $this->stop = is_string($stop) ? [$stop] : array_values($stop);

        return $this;
    }

    /**
     * Set the top_p nucleus-sampling parameter.
     *
     * @return self The current instance for method chaining.
     */
    public function setTopP(float $topP): self
    {
        $this->topP = $topP;

        return $this;
    }

    /**
     * Set the OpenAI-style tool_choice value.
     *
     * @param  string|array<string,mixed>  $toolChoice  "none" | "auto" | "required"
     *                                                  or ["type" => "function", "function" => ["name" => "..."]].
     * @return self The current instance for method chaining.
     */
    public function setToolChoice(string|array $toolChoice): self
    {
        $this->toolChoice = $toolChoice;

        return $this;
    }

    /**
     * Enable or disable token log probabilities in the API response.
     *
     * @return self The current instance for method chaining.
     */
    public function setLogprobs(bool $enabled): self
    {
        $this->logprobs = $enabled;

        return $this;
    }

    /**
     * Set the number of most-likely tokens to include with log probabilities.
     *
     * @return self The current instance for method chaining.
     */
    public function setTopLogprobs(int $count): self
    {
        $this->topLogprobs = $count;

        return $this;
    }

    /**
     * Set the end-user identifier sent as the OpenAI-spec "user" field.
     *
     * @return self The current instance for method chaining.
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Set the thinking-mode configuration array.
     *
     * @param  array<string,mixed>  $config  Typically ["type" => "enabled"] or ["type" => "disabled"].
     * @return self The current instance for method chaining.
     */
    public function setThinking(array $config): self
    {
        $this->thinking = $config;

        return $this;
    }

    /**
     * Set the reasoning effort level ("high" | "max").
     *
     * @return self The current instance for method chaining.
     */
    public function setReasoningEffort(string $effort): self
    {
        $this->reasoningEffort = $effort;

        return $this;
    }

    /**
     * Return the optional v2.2.0 parameters keyed by API field name.
     *
     * Callers MUST filter out null values before merging into the request
     * body — that is what preserves byte-identical request payloads for
     * users who have not opted into any new setter.
     *
     * @return array<string,mixed>
     */
    protected function getOptionalRequestParams(): array
    {
        return [
            QueryFlags::STOP->value => $this->stop,
            QueryFlags::TOP_P->value => $this->topP,
            QueryFlags::TOOL_CHOICE->value => $this->toolChoice,
            QueryFlags::LOGPROBS->value => $this->logprobs,
            QueryFlags::TOP_LOGPROBS->value => $this->topLogprobs,
            QueryFlags::USER->value => $this->userId,
            QueryFlags::THINKING->value => $this->thinking,
            QueryFlags::REASONING_EFFORT->value => $this->reasoningEffort,
        ];
    }
}
