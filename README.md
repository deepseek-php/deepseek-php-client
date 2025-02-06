<p align="center">
  <h1 align="center">DeepSeek PHP Client</h1>
  <p align="center">🚀 Community-Driven PHP SDK for DeepSeek AI API Integration</p>
  
  <p align="center">
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/v/deepseek-php/deepseek-php-client" alt="Latest Version">
    </a>
    <a href="https://php.net">
      <img src="https://img.shields.io/badge/PHP-8.1%2B-blue" alt="PHP Version">
    </a>
    <a href="LICENSE.md">
      <img src="https://img.shields.io/badge/license-MIT-brightgreen" alt="License">
    </a>
    <a href="https://github.com/deepseek-php/deepseek-php-client/actions">
      <img src="https://img.shields.io/github/actions/workflow/status/deepseek-php/deepseek-php-client/tests.yml" alt="Tests Status">
    </a>
  </p>
</p>

## Table of Contents
- [✨ Features](#-features)
- [📦 Installation](#-installation)
- [🚀 Quick Start](#-quick-start)
  - [Basic Usage](#basic-usage)
  - [Advanced Configuration](#advanced-configuration)
  - [Framework Integration](#-framework-integration)
- [🆕 Migration Guide](#-migration-guide)
- [📝 Changelog](#-changelog)
- [🧪 Testing](#-testing)
- [🔒 Security](#-security)
- [🤝 Contributors](#-contributors)
- [📄 License](#-license)

---

## ✨ Features

- **Seamless API Integration**: PHP-first interface for DeepSeek's AI capabilities
- **Fluent Builder Pattern**: Chainable methods for intuitive request building
- **Enterprise Ready**: PSR-18 compliant HTTP client integration
- **Model Flexibility**: Support for multiple DeepSeek models (Coder, Chat, etc.)
- **Streaming Ready**: Built-in support for real-time response handling
- **Framework Friendly**: Laravel & Symfony packages available

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
- Model: `deepseek-chat`
- Temperature: 0.8

### Advanced Configuration

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;

$response = DeepSeekClient::build('your-api-key')
    ->withBaseUrl('https://api.deepseek.com/v2')
    ->withModel(Models::CODER)
    ->withTemperature(1.2)
    ->run();

echo 'API Response:'.$response;
```

### 🛠 Framework Integration

### [Laravel Deepseek Package](https://github.com/deepseek-php/deepseek-laravel)

### [Symfony Deepseek Package](https://github.com/deepseek-php/deepseek-symfony)

---

## 🚧 Migration Guide

Upgrading from v1.x? Check our comprehensive [Migration Guide](MIGRATION.md) for breaking changes and upgrade instructions.

---

## 📝 Changelog

Detailed release notes available in [CHANGELOG.md](CHANGELOG.md)

---

## 🧪 Testing

```bash
composer test
OR
./vendor/bin/pest
```

Test coverage coming in v2.1.

---

## 🔒 Security

**Report Vulnerabilities**: to [omaralwi2010@gmail.com](mailto:omaralwi2010@gmail.com)   

---

## 🤝  Contributors

A huge thank you to these amazing people who have contributed to this project! 🎉💖

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
  </tr>
</table>

**Want to contribute?** Check out the [contributing guidelines](./CONTRIBUTING.md) and submit a pull request! 🚀

---

## 📄 License

This package is open-source software licensed under the [MIT License](LICENSE.md).
