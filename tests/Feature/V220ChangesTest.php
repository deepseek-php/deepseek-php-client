<?php

namespace Tests\Feature;

use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Configs\ReasoningEffort;
use DeepSeek\Enums\Configs\ThinkingType;
use DeepSeek\Enums\Models;
use DeepSeek\Enums\Queries\ToolChoiceMode;
use DeepSeek\Enums\Requests\QueryFlags;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

afterEach(function () {
    Mockery::close();
});

/**
 * Boot a mocked PSR-18 client that captures the outgoing request body string
 * into the supplied reference, and returns a minimal 200 OK JSON body.
 */
function v220MockClient(Psr17Factory $factory, ?string &$capturedBody): ClientInterface
{
    /** @var ClientInterface&LegacyMockInterface&MockInterface $httpClient */
    $httpClient = Mockery::mock(ClientInterface::class);
    $httpClient->shouldReceive('sendRequest')
        ->once()
        ->andReturnUsing(function (RequestInterface $request) use ($factory, &$capturedBody): ResponseInterface {
            $capturedBody = (string) $request->getBody();

            return $factory->createResponse(200)->withBody($factory->createStream('{"id":"ok"}'));
        });

    return $httpClient;
}

// =====================================================================
// Backward-compatibility guards (these MUST pass first)
// =====================================================================

test('run() request body shape is byte-identical when no new setters called', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_FLASH->value)
        ->query('Hello')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toBeArray()
        ->and(array_keys($decoded))
        ->toBe(['messages', 'model', 'stream', 'temperature', 'max_tokens', 'tools', 'response_format']);
});

test('chat() shortcut request body shape is byte-identical when no new setters called', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_FLASH->value)
        ->query('Hello')
        ->chat();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toBeArray()
        ->and(array_keys($decoded))
        ->toBe(['messages', 'model', 'stream', 'temperature', 'max_tokens', 'tools', 'response_format']);
});

test('code() shortcut request body shape is byte-identical when no new setters called', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_PRO->value)
        ->query('def fib(n):')
        ->code();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toBeArray()
        ->and(array_keys($decoded))
        ->toBe(['messages', 'model', 'stream', 'temperature', 'max_tokens', 'tools', 'response_format']);
});

// =====================================================================
// Per-setter additive tests
// =====================================================================

test('setStop(string) adds stop as a single-element array', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setStop('###')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toHaveKey('stop')
        ->and($decoded['stop'])->toBe(['###']);
});

test('setStop(array) adds stop as passed', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setStop(['###', '<END>', "\n\n"])
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['stop'])->toBe(['###', '<END>', "\n\n"]);
});

test('setTopP adds top_p to request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setTopP(0.95)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['top_p'])->toBe(0.95);
});

test('setToolChoice with string "auto" adds tool_choice', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setToolChoice('auto')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tool_choice'])->toBe('auto');
});

test('setToolChoice with named function array adds tool_choice', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    $named = ['type' => 'function', 'function' => ['name' => 'get_weather']];

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setToolChoice($named)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tool_choice'])->toBe($named);
});

test('setToolChoice with "required" reaches the wire', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setToolChoice(ToolChoiceMode::REQUIRED->value)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tool_choice'])->toBe('required');
});

test('setLogprobs(true) adds logprobs to request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setLogprobs(true)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toHaveKey('logprobs')
        ->and($decoded['logprobs'])->toBeTrue();
});

test('setTopLogprobs adds top_logprobs to request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setTopLogprobs(5)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['top_logprobs'])->toBe(5);
});

test('setUserId adds OpenAI-spec user field', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setUserId('user-42')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['user'])->toBe('user-42');
});

test('setThinking adds thinking config to request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setThinking(['type' => ThinkingType::ENABLED->value])
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['thinking'])->toBe(['type' => 'enabled']);
});

test('setReasoningEffort adds reasoning_effort to request body', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setReasoningEffort(ReasoningEffort::MAX->value)
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['reasoning_effort'])->toBe('max');
});

test('all new setters combined produce all new keys plus all existing keys', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->withModel(Models::V4_PRO->value)
        ->query('Hello')
        ->setStop(['###'])
        ->setTopP(0.9)
        ->setToolChoice('auto')
        ->setLogprobs(true)
        ->setTopLogprobs(3)
        ->setUserId('user-42')
        ->setThinking(['type' => 'enabled'])
        ->setReasoningEffort('high')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded)
        ->toHaveKeys([
            'messages', 'model', 'stream', 'temperature', 'max_tokens', 'tools', 'response_format',
            'stop', 'top_p', 'tool_choice', 'logprobs', 'top_logprobs', 'user', 'thinking', 'reasoning_effort',
        ])
        ->and($decoded['model'])->toBe('deepseek-v4-pro');
});

// =====================================================================
// name field on messages (#10)
// =====================================================================

test('query() with no name argument produces a message with only role and content', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('hi')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['messages'])->toBeArray()->toHaveCount(1)
        ->and(array_keys($decoded['messages'][0]))->toBe(['role', 'content'])
        ->and($decoded['messages'][0])->toBe(['role' => 'user', 'content' => 'hi']);
});

test('query() with name argument emits the name field', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('hi', 'user', 'alice')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['messages'][0])
        ->toBe(['role' => 'user', 'content' => 'hi', 'name' => 'alice']);
});

test('buildQuery() with name argument returns the name key', function () {
    $client = new DeepSeekClient(Mockery::mock(ClientInterface::class));

    $result = $client->buildQuery('hi', null, 'bob');

    expect($result)
        ->toBe(['role' => 'user', 'content' => 'hi', 'name' => 'bob']);
});

// =====================================================================
// Tool strict mode helper (#13)
// =====================================================================

test('setStrictTool adds a function tool with strict=true', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    $schema = [
        'type' => 'object',
        'properties' => ['city' => ['type' => 'string']],
        'required' => ['city'],
    ];

    (new DeepSeekClient($httpClient))
        ->query('Weather?')
        ->setStrictTool('get_weather', $schema, 'Get the current weather for a city.')
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tools'])->toBeArray()->toHaveCount(1)
        ->and($decoded['tools'][0])
        ->toBe([
            'type' => 'function',
            'function' => [
                'name' => 'get_weather',
                'description' => 'Get the current weather for a city.',
                'parameters' => $schema,
                'strict' => true,
            ],
        ]);
});

test('setStrictTool called twice appends both tools', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setStrictTool('foo', ['type' => 'object'])
        ->setStrictTool('bar', ['type' => 'object'])
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tools'])->toHaveCount(2)
        ->and($decoded['tools'][0]['function']['name'])->toBe('foo')
        ->and($decoded['tools'][1]['function']['name'])->toBe('bar');
});

test('setTools followed by setStrictTool appends without dropping existing tools', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    $preset = [[
        'type' => 'function',
        'function' => ['name' => 'preset_tool', 'description' => 'preset'],
    ]];

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setTools($preset)
        ->setStrictTool('strict_tool', ['type' => 'object'])
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect($decoded['tools'])->toHaveCount(2)
        ->and($decoded['tools'][0]['function']['name'])->toBe('preset_tool')
        ->and($decoded['tools'][1]['function']['name'])->toBe('strict_tool')
        ->and($decoded['tools'][1]['function']['strict'])->toBeTrue();
});

test('setStrictTool omits the description key when not provided', function () {
    $factory = new Psr17Factory;
    $capturedBody = null;
    $httpClient = v220MockClient($factory, $capturedBody);

    (new DeepSeekClient($httpClient))
        ->query('Hello')
        ->setStrictTool('noop', ['type' => 'object'])
        ->run();

    $decoded = json_decode((string) $capturedBody, true);

    expect(array_keys($decoded['tools'][0]['function']))
        ->toBe(['name', 'parameters', 'strict']);
});

// =====================================================================
// Enum surface
// =====================================================================

test('new QueryFlags cases exist with the expected scalar values', function () {
    expect(QueryFlags::STOP->value)->toBe('stop')
        ->and(QueryFlags::TOP_P->value)->toBe('top_p')
        ->and(QueryFlags::TOOL_CHOICE->value)->toBe('tool_choice')
        ->and(QueryFlags::LOGPROBS->value)->toBe('logprobs')
        ->and(QueryFlags::TOP_LOGPROBS->value)->toBe('top_logprobs')
        ->and(QueryFlags::USER->value)->toBe('user')
        ->and(QueryFlags::THINKING->value)->toBe('thinking')
        ->and(QueryFlags::REASONING_EFFORT->value)->toBe('reasoning_effort');
});

test('ReasoningEffort enum exposes "high" and "max"', function () {
    expect(ReasoningEffort::HIGH->value)->toBe('high')
        ->and(ReasoningEffort::MAX->value)->toBe('max');
});

test('ThinkingType enum exposes "enabled" and "disabled"', function () {
    expect(ThinkingType::ENABLED->value)->toBe('enabled')
        ->and(ThinkingType::DISABLED->value)->toBe('disabled');
});

test('ToolChoiceMode enum exposes "none", "auto", and "required"', function () {
    expect(ToolChoiceMode::NONE->value)->toBe('none')
        ->and(ToolChoiceMode::AUTO->value)->toBe('auto')
        ->and(ToolChoiceMode::REQUIRED->value)->toBe('required');
});
