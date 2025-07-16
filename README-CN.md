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
  - [框架集成](#-框架集成)
- [🆕 迁移指南](#-迁移指南)
- [📝 更新日志](#-更新日志)
- [🧪 测试](#-测试)
- [🔒 安全](#-安全)
- [🤝 贡献者](#-贡献者)
- [📄 许可](#-许可)

---

## ✨ 特性

- **无缝 API 集成**: DeepSeek AI 功能的 PHP 优先接口
- **构建器模式**: 直观的链接请求构建方法
- **企业级别**: 符合 PSR-18 规范
- **模型灵活性**: 支持多种 DeepSeek 模型（Coder、Chat 等）
- **流式传输**: 内置对实时响应处理的支持
- **框架友好**: 提供 Laravel 和 Symfony 包

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
- Model: `deepseek-chat`
- Temperature: 0.8

### Advanced Configuration

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;

$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com/v3', timeout:30, clientType:'guzzle');

$response = $client
    ->withModel(Models::CODER->value)
    ->withStream()
    ->withTemperature(1.2)
    ->setMaxTokens(8192)
    ->setResponseFormat('text')
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

### Use with Symfony HttpClient
the package already built with `symfony Http client`,  if you need to use package with `symfony` Http Client , it is easy to achieve that, just pass `clientType:'symfony'` with `build` function.

ex with symfony:

```php
//  with defaults baseUrl and timeout
$client = DeepSeekClient::build('your-api-key', clientType:'symfony')
// with customization
$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com/v3', timeout:30, clientType:'symfony');

$client->query('Explain quantum computing in simple terms')
       ->run();
```

### 获取模型列表

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->getModelsList()
    ->run();

echo $response; // {"object":"list","data":[{"id":"deepseek-chat","object":"model","owned_by":"deepseek"},{"id":"deepseek-reasoner","object":"model","owned_by":"deepseek"}]}
```

### 函数调用

**函数调用**允许模型调用外部工具以增强其功能。
你可以在文档中查看有关函数调用的详细信息：
[FUNCTION-CALLING.md](docs/FUNCTION-CALLING.md)


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

## 🤝  贡献者

非常感谢为这个项目做出贡献的人！ 🎉💖

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/omaralalwi">
        <img src="https://avatars.githubusercontent.com/u/25439498?v=4" width="60px;" style="border-radius:50%;" alt="Omar AlAlwi"/>
        <br />
        <b>Omar AlAlwi</b>
      </a>
      <br />
      🏆 Creator
    </td>
    <td align="center">
      <a href="https://github.com/aymanalhattami">
        <img src="https://avatars.githubusercontent.com/u/34315778?v=4" width="60px;" style="border-radius:50%;" alt="Ayman Alhattami"/>
        <br />
        <b>Ayman Alhattami</b>
      </a>
      <br />
      ⭐ Contributor
    </td>
    <td align="center">
      <a href="https://github.com/moassaad">
        <img src="https://avatars.githubusercontent.com/u/155223476?v=4" width="60px;" style="border-radius:50%;" alt="Mohammad Asaad"/>
        <br />
        <b>Mohammad Asaad</b>
      </a>
      <br />
      ⭐ Contributor
    </td>
    <td align="center">
      <a href="https://github.com/OpadaAlzaiede">
        <img src="https://avatars.githubusercontent.com/u/48367429?v=4" width="60px;" style="border-radius:50%;" alt="Opada Alzaiede"/>
        <br />
        <b>Opada Alzaiede</b>
      </a>
      <br />
      ⭐ Contributor
    </td>
    <td align="center">
      <a href="https://github.com/hishamco">
        <img src="https://avatars.githubusercontent.com/u/3237266?v=4" width="60px;" style="border-radius:50%;" alt="Hisham Bin Ateya"/>
        <br />
        <b>Hisham Bin Ateya</b>
      </a>
      <br />
      ⭐ Contributor
    </td>
    <td align="center">
      <a href="https://github.com/VinchanGit">
        <img src="https://avatars.githubusercontent.com/u/38177046?v=4" width="60px;" style="border-radius:50%;" alt="陈文锋"/>
        <br />
        <b>陈文锋</b>
      </a>
      <br />
      ⭐ Contributor
    </td>
  </tr>
</table>

**想要贡献？** 查看 [contributing guidelines](./CONTRIBUTING.md) 并提交拉取请求！ 🚀

---

## 📄 许可

基于 [MIT License](LICENSE.md) 开源协议。
