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

**What's been delivered in the `v2.x` line so far:**
- **v2.1.0** — V4 models, base-URL fix, `chat()` / `code()` parameter wiring, keep-alive line stripping.
- **v2.2.0** — thinking mode (`setThinking`, `setReasoningEffort`), `setStop`, `setTopP`, `setToolChoice` (including the previously missing `"required"` mode), `setLogprobs`, `setTopLogprobs`, `setUserId`, OpenAI-spec `name` field on messages, and the `setStrictTool` helper.

**What's still coming in the `v2.x` line:** FIM completion, real SSE streaming, chat prefix completion (Beta), user balance endpoint, rate-limit handling, response-introspection accessors, and additional DX helpers — all additive.

**What's reserved for `v3.0.0`:** Removing deprecated `Models::CODER` / `Models::R1` / `Models::R1Zero`, removing the `Coder` class and `HasCoder` trait, raising default `max_tokens`, retyping `run()` to return a structured DTO, expanding `ClientContract` to match the implementation. All of these will be announced via `@deprecated` notices throughout `v2.x` and shipped with a complete migration guide in [MIGRATION.md](MIGRATION.md).

See [TODO.md](TODO.md) for the full feature gap analysis with per-item BC classification.

---

## [Unreleased] - v2.2.x (planned follow-ups)

> Additive only. Zero breaking changes from v2.x. See [TODO.md](TODO.md) for the source list.

### Still planned for the v2.2.x line
- `setSystemMessage(string $content)` convenience method.
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

## [2.2.0] - 2026-05-25

> Additive parameter setters. Zero breaking changes from `v2.1.x`. Existing callers who do not opt into any of the new setters receive a byte-identical request body compared to v2.1.x — this is enforced by the regression tests in [`tests/Feature/V220ChangesTest.php`](tests/Feature/V220ChangesTest.php).

### Added

#### Generation parameter setters (all optional, all omitted from the request body until explicitly invoked)

- **Thinking mode** (`TODO.md` #4) — [`setThinking(array $config)`](src/Traits/Client/HasGenerationParams.php) and [`setReasoningEffort(string $effort)`](src/Traits/Client/HasGenerationParams.php). See the [DeepSeek reasoning_model docs](https://api-docs.deepseek.com/guides/reasoning_model).
- **Stop sequences** (`TODO.md` #5) — `setStop(string|array $stop)`. Single strings are normalized to a one-element array.
- **Nucleus sampling** (`TODO.md` #6) — `setTopP(float $topP)`.
- **Tool choice** (`TODO.md` #7) — `setToolChoice(string|array $toolChoice)`. Accepts `"none"`, `"auto"`, `"required"`, or the named-function array shape `['type' => 'function', 'function' => ['name' => '...']]`. The previously missing `"required"` mode is now reachable.
- **Log probabilities** (`TODO.md` #8) — `setLogprobs(bool $enabled)` and `setTopLogprobs(int $count)`.
- **End-user identifier** (`TODO.md` #9) — `setUserId(string $userId)`. Sent on the wire as the OpenAI-spec `user` field.

#### Message-level additions

- **OpenAI-spec `name` field on messages** (`TODO.md` #10) — optional 3rd parameter `?string $name = null` added to [`DeepSeekClient::query()`](src/DeepSeekClient.php) and [`DeepSeekClient::buildQuery()`](src/DeepSeekClient.php). The `name` key is omitted entirely from the message when null, preserving the existing 2-argument behavior byte-for-byte.

#### Function-calling additions

- **Structured tool `strict` mode helper** (`TODO.md` #13) — new [`setStrictTool(string $name, array $parameters, ?string $description = null)`](src/Traits/Client/HasToolsFunctionCalling.php) appends a function tool with `strict: true` to the existing `tools` array. The existing `setTools(array)` API is unchanged.

#### Enums and helpers

- New `QueryFlags` cases: `STOP`, `TOP_P`, `TOOL_CHOICE`, `LOGPROBS`, `TOP_LOGPROBS`, `USER`, `THINKING`, `REASONING_EFFORT`.
- New [`DeepSeek\Enums\Configs\ReasoningEffort`](src/Enums/Configs/ReasoningEffort.php) enum with `HIGH` and `MAX` cases.
- New [`DeepSeek\Enums\Configs\ThinkingType`](src/Enums/Configs/ThinkingType.php) enum with `ENABLED` and `DISABLED` cases.
- New [`DeepSeek\Enums\Queries\ToolChoiceMode`](src/Enums/Queries/ToolChoiceMode.php) enum with `NONE`, `AUTO`, and `REQUIRED` cases.
- New [`DeepSeek\Traits\Client\HasGenerationParams`](src/Traits/Client/HasGenerationParams.php) trait composed into [`DeepSeekClient`](src/DeepSeekClient.php). Holds all eight new setters and their backing nullable state.

### Internal

- `run()`, `chat()`, `code()` now merge the new optional parameters via an omit-when-null loop. The original seven request-body keys (`messages`, `model`, `stream`, `temperature`, `max_tokens`, `tools`, `response_format`) remain in their original order and are sent unchanged regardless of whether any new setter is called. This is verified by the three "byte-identical when no new setters called" tests at the top of [`tests/Feature/V220ChangesTest.php`](tests/Feature/V220ChangesTest.php).
- 23 new tests cover BC guards, every new setter, the `name` field, the strict-tool helper, and the new enum surface. The existing test suite (DeepSeekClientTest, V210ChangesTest, FunctionCallingTest) continues to pass unchanged.

### Backward-compatibility notes

- No public method, class, trait, enum case, or constant was removed or renamed.
- No existing method signature changed in a breaking way. The new optional `?string $name` parameter on `query()` / `buildQuery()` follows the same additive pattern as the `?string $clientType = null` parameter added to `build()` in v2.1.0.
- `ClientContract` is untouched; the new setters are introduced via the additive `HasGenerationParams` trait on the concrete client.

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
