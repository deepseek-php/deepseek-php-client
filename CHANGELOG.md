# Changelog

All notable changes to `deepseek-php-client` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## Backward Compatibility Commitment

This package is used in 100k+ production installs. We take backward compatibility seriously and follow semantic versioning strictly within the `v2.x` line.

**Our commitments for the entire `v2.x` line:**

- No public method, class, trait, enum case, or constant will be **removed** or **renamed**.
- No public method's **return type** will change.
- No public method's existing **parameters** will change type or be removed (new optional parameters with defaults may be added).
- No published interface ([`ClientContract`](src/Contracts/ClientContract.php), `ResourceContract`, `ResultContract`, `ApiFactoryContract`) will gain new required methods. New methods on the client implementation will be exposed via separate, additive interfaces.
- Default behavior of existing methods will not silently change in ways that affect cost, output, or correctness (e.g. raising default `max_tokens`).

**What's coming in `v2.1.x`:** Every new DeepSeek API feature (V4 models, thinking mode, FIM completion, Anthropic format, user balance, `stop` / `top_p` / `tool_choice` / `logprobs` / `user_id`, chat prefix completion, etc.) will be delivered as **additive** new methods, optional parameters, and enum cases. Bug fixes (`/v3` base URL, ignored params in `chat()` / `code()`, keep-alive line stripping) are also in scope.

**What's reserved for `v3.0.0`:** Removing deprecated `Models::CODER` / `Models::R1` / `Models::R1Zero`, removing the `Coder` class and `HasCoder` trait, raising default `max_tokens`, retyping `run()` to return a structured DTO, expanding `ClientContract` to match the implementation. All of these will be announced via `@deprecated` notices throughout `v2.x` and shipped with a complete migration guide in [MIGRATION.md](MIGRATION.md).

See [TODO.md](TODO.md) for the full feature gap analysis with per-item BC classification.

---

## [Unreleased] - v2.2.0 (planned)

> Additive only. Zero breaking changes from v2.x. See [TODO.md](TODO.md) for the source list.

### Generation parameters and DX
- Thinking mode setters: `setThinking(array $config)` and `setReasoningEffort(string $effort)`.
- New sampling / generation parameter setters: `setStop()`, `setTopP()`, `setToolChoice()`, `setLogprobs()`, `setTopLogprobs()`, `setUserId()`.
- Optional `?string $name` parameter on `query()` and `buildQuery()` for the OpenAI message `name` field.
- `setSystemMessage(string $content)` convenience method.
- Tool `strict` mode helper for function definitions.
- Response introspection accessors on `SuccessResult`: `getMessage()`, `getUsage()`, `getReasoningContent()`, `getToolCalls()`, `getFinishReason()`, `getCacheHitTokens()` — `run()` continues to return `string`.
- `getQueries()`, `getConfig()`, `reset()` introspection helpers.
- `DefaultConfigs::MAX_TOKENS` and `DefaultConfigs::RESPONSE_FORMAT_TYPE` cases (`TemperatureValues::MAX_TOKENS` / `RESPONSE_FORMAT_TYPE` deprecated).
- Additive `ExtendedClientContract` interface (preserves the existing `ClientContract`).

## [Unreleased] - v2.3.0 (planned)

- Real streaming via new `runStreamed(callable $onChunk): void` method (existing `->withStream()->run()` string-returning behavior preserved).
- `stream_options.include_usage` exposure.
- Chat Prefix Completion (Beta): `queryAssistantPrefix(string $content)` and `/beta` base URL opt-in.
- `getUserBalance()` method, `EndpointSuffixes::USER_BALANCE` enum case, and `UserBalanceResult` DTO.
- FIM Completion (Beta): new resource class for `POST /completions` (Fill-In-the-Middle).
- Rate-limit handling: new `RateLimitResult` class with parsed `Retry-After` header for HTTP 429 responses.

> Note: Anthropic API format support is intentionally not on the v2.x roadmap. It may be reintroduced in a later release if there is sufficient community demand.

---

## [2.1.0] - 2026-05-22

> Foundation + Bug Fixes. Zero breaking changes from `v2.0.x`. All deprecated symbols remain fully functional throughout the `v2.x` line.

### Added
- New `Models::V4_PRO` (`deepseek-v4-pro`) and `Models::V4_FLASH` (`deepseek-v4-flash`) enum cases. Both models support DeepSeek's 1M-token context window and dual thinking / non-thinking modes (per the [V4 Preview announcement](https://api-docs.deepseek.com/news/news260424)).

### Fixed
- **Default `baseUrl` corrected** from `https://api.deepseek.com/v3` to `https://api.deepseek.com`. The `/v3` path was never a valid DeepSeek API endpoint. Users who passed an explicit `baseUrl` to `DeepSeekClient::build()` are unaffected.
- **`chat()` and `code()` shortcuts** now honor `temperature`, `maxTokens`, `tools`, and `responseFormat` set on the client. Previously these settings were silently dropped from the request body when using the shortcut methods; the request body now matches what `run()` sends.
- **Keep-Alive padding stripped from responses.** The DeepSeek API may send empty lines (non-streaming) or `: keep-alive` SSE comments (streaming) while waiting for inference to start. These are now removed before the response content is exposed to user code. See the [DeepSeek Rate Limit docs](https://api-docs.deepseek.com/quick_start/rate_limit#request-keep-alive-mechanism) for details.

### Deprecated
The following symbols are deprecated and will be removed in `v3.0.0`. They remain fully functional throughout the `v2.x` line — only IDE `@deprecated` notices are emitted (no `trigger_error()`).

- `Models::CHAT` — the `deepseek-chat` alias retires from the DeepSeek API on 2026-07-24. Use `Models::V4_FLASH` (with `setThinking(['type' => 'disabled'])` in v2.2.0+ for non-thinking mode).
- `Models::CODER` — `deepseek-coder` no longer exists in the DeepSeek API. Use `Models::V4_PRO` or `Models::V4_FLASH`.
- `Models::R1` — the `DeepSeek-R1` alias retires from the DeepSeek API on 2026-07-24. Use `Models::V4_FLASH` (with `setThinking(['type' => 'enabled'])` in v2.2.0+ for thinking mode).
- `Models::R1Zero` — `DeepSeek-R1-Zero` was never a valid DeepSeek API model id.

### Documentation
- README refreshed: default temperature corrected (1.3, not 0.8); `baseUrl` example updated; advanced-configuration example now uses `Models::V4_PRO`; `getModelsList()` example output updated to include V4 models; new "Supported Models" callout under Features.
- `docs/FUNCTION-CALLING.md` updated: JSON examples use `deepseek-v4-pro`; new "Thinking-mode caveat" section explaining that `reasoning_content` must be echoed back on tool turns to avoid HTTP 400 from the API.

### Internal
- New test file: `tests/Feature/V210ChangesTest.php` covering V4 enum cases, default base URL, Keep-Alive stripping (both non-streaming and streaming), and `chat()` / `code()` request body completeness.

---

## [2.0.6] - 2025

Current published baseline prior to v2.1.0. No breaking changes from `2.0.0`.

Patch-level fixes and documentation updates rolled up since `2.0.0`. This release marks the point from which the [Backward Compatibility Commitment](#backward-compatibility-commitment) above applies.

---

## [2.0.0] - 2025-02-01

### Changed (Breaking)
- **Namespace renamed** from `DeepseekPhp` to `DeepSeek`. All imports in user code must be updated. See [MIGRATION.md](MIGRATION.md) for details.

  Replace:
  ```php
  use DeepseekPhp\SomeClass;
  ```
  With:
  ```php
  use DeepSeek\SomeClass;
  ```

---

## [Roadmap] - v3.0.0 (no ETA)

> Breaking changes only. Will ship with a complete migration guide in [MIGRATION.md](MIGRATION.md). Users will get at least one full `v2.1.x` release with `@deprecated` notices before any of these land.

### Removed
- Deprecated `Models` enum cases: `CHAT`, `CODER`, `R1`, `R1Zero` (per the 2026-07-24 DeepSeek API retirement of the `deepseek-chat` and `deepseek-reasoner` aliases).
- `Resources\Coder` class and `Traits\Resources\HasCoder` trait (including `code()`).
- `Enums\Configs\TemperatureValues::MAX_TOKENS` and `RESPONSE_FORMAT_TYPE` cases.

### Changed (Breaking)
- `run()` will return a structured `ChatCompletionResult` DTO instead of a raw JSON `string`. The current string-returning behavior moves to a new method (`runRaw()` or equivalent).
- Default `MAX_TOKENS` raised from 4096 to a V4-appropriate value (V4 models support 384K output tokens — current default is ~1% of capacity).
- `ClientContract` expanded to declare all methods the implementation provides: `resetQueries()`, `setTemperature()`, `setMaxTokens()`, `setResponseFormat()`, `setResult()`, `getResult()`, and the 4-arg `build()` signature including `?string $clientType`.
- Default `baseUrl` no longer accepts the legacy `/v3` suffix (already removed in `v2.1.x` as a fix; v3.0.0 removes the back-compat shim).

---

## [1.0.0] - 201X-XX-XX

- Initial release.
