<p align="center">
<h1 align="center">DeepSeek PHP Client</h1>
<p align="center">⚡️ A community-driven, open-source PHP client for DeepSeek AI, officially listed in the DeepSeek API documentation and built for expressive, production-ready AI integrations.</p>

  <p align="center">
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/v/deepseek-php/deepseek-php-client" alt="Latest Version">
    </a>
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/dt/deepseek-php/deepseek-php-client" alt="Total Downloads">
    </a>
    <a href="https://php.net">
      <img src="https://img.shields.io/badge/PHP-8.1%2B-blue" alt="PHP Version">
    </a>
    <a href="LICENSE.md">
      <img src="https://img.shields.io/badge/license-MIT-brightgreen" alt="License">
    </a>
    <a href="https://github.com/deepseek-php/deepseek-php-client/stargazers">
      <img src="https://img.shields.io/github/stars/deepseek-php/deepseek-php-client?style=social" alt="GitHub Stars">
    </a>
  </p>

[AR](README-AR.md) | [CN](README-CN.md)

## Table of Contents
- [✨ Features](#-features)
- [📦 Installation](#-installation)
- [🚀 Quick Start](#-quick-start)
  - [Basic Usage](#basic-usage)
  - [Advanced Configuration](#advanced-configuration)
  - [important warning with json mode](#-deepseek-json-mode-requirement)
  - [Use with Symfony HttpClient](#use-with-symfony-httpclient)
  - [Get Models List](#get-models-list)
  - [Function Calling](#function-calling)
  - [Framework Integration](#-framework-integration)
- [🆕 Migration Guide](#-migration-guide)
- [📝 Changelog](#-changelog)
- [🧪 Testing](#-testing)
- [🔒 Security](#-security)
- [📄 License](#-license)

---

## ✨ Features

- **Seamless API Integration**: PHP-first interface for DeepSeek's AI capabilities.
- **Fluent Builder Pattern**: Chainable methods for intuitive request building.
- **Enterprise Ready**: PSR-18 compliant HTTP client integration.
- **Latest DeepSeek V4 Models**: First-class support for `deepseek-v4-pro` and `deepseek-v4-flash` with 1M-token context windows and thinking / non-thinking modes.
- **Streaming Ready**: Built-in support for real-time response handling.
- **Many Http Clients**: easy to use `Guzzle http client` (default) , or `symfony http client`.
- **Framework Friendly**: Laravel & Symfony packages available.

> **Supported Models**
>
> - `Models::V4_PRO` — flagship 1.6T/49B-active model, max 384K output tokens.
> - `Models::V4_FLASH` — fast, economical 284B/13B-active model, max 384K output tokens.
>
> Legacy `Models::CHAT`, `Models::CODER`, `Models::R1`, and `Models::R1Zero` are deprecated and will be removed in v3.0.0. The `deepseek-chat` and `deepseek-reasoner` aliases retire from the DeepSeek API on **2026-07-24**.

---

## 📦 Installation

Require the package via Composer:

```bash
composer require deepseek-php/deepseek-php-client
```

**Requirements**:
- PHP 8.1+

---

## 🚀 Quick Start

### Basic Usage

Get started with just two lines of code:

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->query('Explain quantum computing in simple terms')
    ->run();

echo $response;
```

📌 Defaults used:
- Model: API default (no `model` field sent unless you call `withModel()`)
- Temperature: 1.3 (`TemperatureValues::GENERAL_CONVERSATION`)
- Max tokens: 4096
- Response format: `text`

### Advanced Configuration

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;

$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'guzzle');

$response = $client
    ->withModel(Models::V4_PRO->value)
    ->withStream()
    ->setTemperature(1.2)
    ->setMaxTokens(8192)
    ->setResponseFormat('text') // or "json_object"  with careful .
    ->query('Explain quantum computing in simple terms')
    ->run();

echo 'API Response:'.$response;
```

## ⚠️ DeepSeek JSON Mode Requirement

When using:

```php
->setResponseFormat('json_object')
```

Your prompt **must contain the word `"json"`** in some form. Otherwise, the API will reject the request with the following error:

> `"Prompt must contain the word 'json' in some form to use 'response_format' of type 'json_object'"`

---

### 🚫 Incorrect Usage

```php
->setResponseFormat('json_object')
->query('Explain quantum computing in simple terms')
```

### ✅ Correct Usage

```php
->setResponseFormat('json_object')
->query('Respond in valid JSON format. Explain quantum computing in simple terms.')
```

> ✅ **Tip**: For best results, also provide a JSON example or explicitly say:
> *"Respond only in valid JSON."*


---

### Use with Symfony HttpClient
the package already built with `symfony Http client`,  if you need to use package with `symfony` Http Client , it is easy to achieve that, just pass `clientType:'symfony'` with `build` function.
 
ex with symfony:

```php
//  with defaults baseUrl and timeout
$client = DeepSeekClient::build('your-api-key', clientType:'symfony')
// with customization
$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'symfony');

$client->query('Explain quantum computing in simple terms')
       ->run();
```

### Get Models List

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
//     {"id": "deepseek-chat",     "object": "model", "owned_by": "deepseek"},     // deprecated, retires 2026-07-24
//     {"id": "deepseek-reasoner", "object": "model", "owned_by": "deepseek"}      // deprecated, retires 2026-07-24
//   ]
// }
```


### Function Calling

Function Calling allows the model to call external tools to enhance its capabilities.[[1]](https://api-docs.deepseek.com/guides/function_calling)

You Can check the documentation for function calling in [FUNCTION-CALLING.md](docs/FUNCTION-CALLING.md)


### 🛠 Framework Integration

### [Laravel Deepseek Package](https://github.com/deepseek-php/deepseek-laravel)

---

## 🚧 Migration Guide

Upgrading from v1.x? Check our comprehensive [Migration Guide](MIGRATION.md) for breaking changes and upgrade instructions.

---

## 📝 Changelog

Detailed release notes available in [CHANGELOG.md](CHANGELOG.md)

---

## 🧪 Testing

```bash
./vendor/bin/pest
```

Test coverage coming in v2.1.

---
<div>

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

</div>

---

## 🔒 Security

**Report Vulnerabilities**: to [omaralwi2010@gmail.com](mailto:omaralwi2010@gmail.com)   

---

## 📄 License

This package is open-source software licensed under the [MIT License](LICENSE.md).
