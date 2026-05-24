<p align="center">
  <h1 align="center">DeepSeek PHP 客户端</h1>
  <p align="center">🚀 由社区驱动的 PHP SDK，用于集成 DeepSeek AI API</p>
  
  <p align="center">
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/v/deepseek-php/deepseek-php-client" alt="最新版本">
    </a>
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/dt/deepseek-php/deepseek-php-client" alt="总下载次数">
    </a>
    <a href="https://php.net">
      <img src="https://img.shields.io/badge/PHP-8.1%2B-blue" alt="PHP 版本">
    </a>
    <a href="LICENSE.md">
      <img src="https://img.shields.io/badge/license-MIT-brightgreen" alt="许可证">
    </a>
    <a href="https://github.com/deepseek-php/deepseek-php-client/stargazers">
      <img src="https://img.shields.io/github/stars/deepseek-php/deepseek-php-client?style=social" alt="GitHub 收藏数">
    </a>
  </p>

[EN](README.md) | [AR](README-AR.md)

## 目录
- [✨ 特性](#-特性)
- [📦 安装](#-安装)
- [🚀 快速入门](#-快速入门)
  - [基本用法](#基本用法)
  - [高级配置](#高级配置)
  - [使用 JSON 模式的重要警告](#-deepseek-json-模式使用要求)
  - [使用 Symfony HttpClient](#使用-symfony-httpclient)
  - [获取模型列表](#获取模型列表)
  - [函数调用](#函数调用)
  - [生成参数（v2.2.0）](#生成参数v220)
    - [思考模式与推理强度](#思考模式与推理强度)
    - [停止序列（stop）](#停止序列stop)
    - [核采样（top_p）](#核采样top_p)
    - [工具选择控制（tool_choice）](#工具选择控制tool_choice)
    - [对数概率（logprobs）](#对数概率logprobs)
    - [最终用户标识](#最终用户标识)
    - [消息 name 字段](#消息-name-字段)
    - [严格模式工具（strict）](#严格模式工具strict)
  - [框架集成](#-框架集成)
- [🆕 迁移指南](#-迁移指南)
- [📝 更新日志](#-更新日志)
- [🧪 测试](#-测试)
- [🔒 安全](#-安全)
- [📄 许可](#-许可)

---

## ✨ 特性

- **无缝 API 集成**: DeepSeek AI 功能的 PHP 优先接口
- **构建器模式**: 直观的链接请求构建方法
- **企业级别**: 符合 PSR-18 规范
- **最新 DeepSeek V4 模型**: 一流支持 `deepseek-v4-pro` 和 `deepseek-v4-flash`，具备 1M 令牌上下文窗口及思考 / 非思考模式
- **完整的生成参数控制（v2.2.0）**: 提供思考模式、推理强度、停止序列、`top_p`、工具选择（`none` / `auto` / `required` / 命名函数）、`logprobs`、最终用户标识、消息 `name` 字段以及严格模式工具的链式 setter
- **流式传输**: 内置对实时响应处理的支持
- **框架友好**: 提供 Laravel 和 Symfony 包

> **受支持的模型**
>
> - `Models::V4_PRO` — 旗舰模型，最大输出 384K 令牌。
> - `Models::V4_FLASH` — 快速、经济，最大输出 384K 令牌。
>
> 旧版 `Models::CHAT`、`Models::CODER`、`Models::R1` 与 `Models::R1Zero` 已弃用，将在 v3.0.0 中移除。`deepseek-chat` 与 `deepseek-reasoner` 别名将于 **2026-07-24** 从 DeepSeek API 中下线。

---

## 📦 安装

通过 Composer 安装:

```bash
composer require deepseek-php/deepseek-php-client
```

**要求**:
- PHP 8.1+

---

## 🚀 快速入门

### 基本用法

只需两行代码即可开始:

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->query('Explain quantum computing in simple terms')
    ->run();

echo $response;
```

📌 默认配置:
- 模型: API 默认（除非调用 `withModel()`，否则不发送 `model` 字段）
- 温度: 1.3（`TemperatureValues::GENERAL_CONVERSATION`）
- 最大令牌数: 4096
- 响应格式: `text`

### 高级配置

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;

$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'guzzle');

$response = $client
    ->withModel(Models::V4_PRO->value)
    ->withStream()
    ->setTemperature(1.2)
    ->setMaxTokens(8192)
    ->setResponseFormat('text') // 或 "json_object"，请谨慎使用。
    ->query('Explain quantum computing in simple terms')
    ->run();

echo 'API Response:'.$response;
```


## ⚠️ DeepSeek JSON 模式使用要求

当使用：

```php
->setResponseFormat('json_object')
```

你的提示语（prompt）**必须包含 "json" 这个词**，否则 API 会返回以下错误：

> `"Prompt must contain the word 'json' in some form to use 'response_format' of type 'json_object'"`

---

### 🚫 错误示例

```php
->setResponseFormat('json_object')
->query('用简单的语言解释量子计算')
```

### ✅ 正确示例

```php
->setResponseFormat('json_object')
->query('请以有效的 JSON 格式回答，并用简单语言解释量子计算。')
```

> ✅ **建议**：为了获得更好的结果，最好也在提示中提供一个 JSON 示例，并强调 “只返回 JSON”。


---

### 使用 Symfony HttpClient

本包已内置 `symfony Http client`。若需使用 Symfony 的 HTTP 客户端，只需在 `build` 函数中传入 `clientType:'symfony'` 即可。

Symfony 示例：

```php
// 使用默认的 baseUrl 和 timeout
$client = DeepSeekClient::build('your-api-key', clientType:'symfony')
// 自定义配置
$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'symfony');

$client->query('Explain quantum computing in simple terms')
       ->run();
```

### 获取模型列表

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->getModelsList()
    ->run();

echo $response;
// {
//   "object": "list",
//   "data": [
//     {"id": "deepseek-v4-pro",   "object": "model", "owned_by": "deepseek"},
//     {"id": "deepseek-v4-flash", "object": "model", "owned_by": "deepseek"},
//     {"id": "deepseek-chat",     "object": "model", "owned_by": "deepseek"},     // 已弃用，2026-07-24 下线
//     {"id": "deepseek-reasoner", "object": "model", "owned_by": "deepseek"}      // 已弃用，2026-07-24 下线
//   ]
// }
```

### 函数调用

**函数调用**允许模型调用外部工具以增强其功能。
你可以在文档中查看有关函数调用的详细信息：
[FUNCTION-CALLING.md](docs/FUNCTION-CALLING.md)

---

### 生成参数（v2.2.0）

从 `v2.2.0` 起，本客户端通过链式 setter 暴露了完整的 DeepSeek 生成参数。**所有新 setter 都是可选的**：若不调用，请求体与 v2.1.x 完全一致 — 无需任何迁移。

#### 思考模式与推理强度

V4 模型支持专门的"思考"推理阶段。使用 [`setThinking()`](src/Traits/Client/HasGenerationParams.php) 切换开关，使用 [`setReasoningEffort()`](src/Traits/Client/HasGenerationParams.php) 在 `high`（普通请求的默认值）与 `max`（推荐用于 Agent 流程）之间选择。

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Configs\ReasoningEffort;
use DeepSeek\Enums\Configs\ThinkingType;
use DeepSeek\Enums\Models;

$response = DeepSeekClient::build('your-api-key')
    ->withModel(Models::V4_PRO->value)
    ->setThinking(['type' => ThinkingType::ENABLED->value])
    ->setReasoningEffort(ReasoningEffort::MAX->value)
    ->query('Prove that there are infinitely many primes.')
    ->run();
```

> ⚠️ 在思考模式下，DeepSeek API 会静默忽略 `temperature` / `top_p`，并对 `logprobs` / `top_logprobs` 返回 HTTP 400。请参考 [推理模型文档](https://api-docs.deepseek.com/guides/reasoning_model)。

#### 停止序列（stop）

最多 16 个停止序列。可传入字符串或数组；单个字符串会被规范化为单元素数组。

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Write a haiku')
    ->setStop(['###', "\n\n"])
    ->run();
```

#### 核采样（top_p）

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Tell me a short story')
    ->setTopP(0.95)
    ->run();
```

#### 工具选择控制（tool_choice）

[`setToolChoice()`](src/Traits/Client/HasGenerationParams.php) 接受 `"none"`、`"auto"`、`"required"`（强制调用工具）或命名函数数组形式。之前缺失的 `"required"` 模式现在已可用。

```php
use DeepSeek\Enums\Queries\ToolChoiceMode;

// 强制模型调用任意工具
$client->setTools($tools)
       ->setToolChoice(ToolChoiceMode::REQUIRED->value);

// 强制模型调用指定名称的函数
$client->setTools($tools)
       ->setToolChoice([
           'type' => 'function',
           'function' => ['name' => 'get_weather'],
       ]);
```

#### 对数概率（logprobs）

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Hello')
    ->setLogprobs(true)
    ->setTopLogprobs(5)
    ->run();
```

#### 最终用户标识

用于速率限制隔离、内容安全和 KV 缓存隔离。在请求中以 OpenAI 规范的 `user` 字段发送。

```php
$response = DeepSeekClient::build('your-api-key')
    ->setUserId('user-42')
    ->query('Hello')
    ->run();
```

#### 消息 name 字段

`query()`（与 `buildQuery()`）的可选第 3 个参数，遵循 OpenAI 规范用于区分同一角色下的不同参与者。原有的两参数调用保持不变。

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Hello, I am Alice.', 'user', 'alice')
    ->query('Hello, I am Bob.',   'user', 'bob')
    ->run();
```

#### 严格模式工具（strict）

[`setStrictTool()`](src/Traits/Client/HasToolsFunctionCalling.php) 追加一个带 `strict: true` 的函数工具，强制模型生成完全符合 JSON Schema 的参数。可与 `setTools()` 安全组合 — 它是追加而非替换。

```php
$response = DeepSeekClient::build('your-api-key')
    ->setStrictTool(
        name: 'get_weather',
        parameters: [
            'type' => 'object',
            'properties' => ['city' => ['type' => 'string']],
            'required' => ['city'],
        ],
        description: 'Get the current weather for a city.',
    )
    ->query('What is the weather like in Cairo?')
    ->run();
```

---

### 🛠 框架集成

### [Laravel Deepseek Package](https://github.com/deepseek-php/deepseek-laravel)


# 🐘✨ **DeepSeek PHP Community** ✨🐘

Click the button bellow or [join here](https://t.me/deepseek_php_community) to be part of our growing community!

[![Join Telegram](https://img.shields.io/badge/Join-Telegram-blue?style=for-the-badge&logo=telegram)](https://t.me/deepseek_php_community)

### **Channel Structure** 🏗️
- 🗨️ **General** - Daily chatter
- 💡 **Ideas & Suggestions** - Shape the community's future
- 📢 **Announcements & News** - Official updates & news
- 🚀 **Releases & Updates** - Version tracking & migration support
- 🐞 **Issues & Bug Reports** - Collective problem-solving
- 🤝 **Pull Requests** - Code collaboration & reviews

---

## 🚧 迁移指南

从 v1.x 升级？请查看我们全面的 [迁移指南](MIGRATION.md) 了解重大变更和升级说明。

---

## 📝 更新日志

详细的发布说明可在 [CHANGELOG.md](CHANGELOG.md) 查看。

---

## 🧪 测试

```bash
./vendor/bin/pest
```

测试覆盖范围涵盖 v2.1。

---

## 🔒 安全

**报告漏洞**: [omaralwi2010@gmail.com](mailto:omaralwi2010@gmail.com)

---



## 📄 许可

基于 [MIT License](LICENSE.md) 开源协议。
