<p align="center">
  <a href="https://deepseek-php/deepseek-php-client" target="_blank">
    <img src="https://raw.githubusercontent.com/deepseek-php/deepseek-php-client/master/public/images/deepseek_screenshot.png" alt="Gpdf">
  </a>
</p>

# Deepseek PHP Client

## Table of Contents
- [Overview](#Overview)
   - [Features](#key-Features)
- [Installation](#installation)
- [Quick Start Guide](#quick-start-guide)
    - [Basic Usage](#basic-usage)
    - [Advanced Usage](#advanced-usage)
    - [Use With Frameworks](#use-with-frameworks)
- [Testing](#testing)
- [Contributors](#contributors-)
- [License](#license)

---
## Overview
**Deepseek PHP Client** is a robust and community-driven PHP client library for seamless integration with the [Deepseek](https://www.deepseek.com/) API.
### Key Features
- **Easy Integration:** Simplifies interaction with the Deepseek API using a PHP client.
- **Method Chaining:** Supports fluent method chaining for building requests.
- **Customizable:** Allows setting different models, query roles, and streaming options.
- **PSR-18 Compliance:** Utilizes PSR-18 HTTP client for making API requests.

---

## Installation

You can install the package via Composer:

```bash
composer require deepseek-php/deepseek-php-client
```

**Ensure your project meets the following requirements:**
- PHP 8.1 or later

---

## Quick Start Guide

### Basic Usage

```php
use DeepseekPhp\DeepseekClient;

$apiKey = 'your-api-key';

$response = DeepseekClient::build($apiKey)
    ->query('Hello Deepseek, how are you today?')
    ->run();

echo 'API Response:'.$response;
```

**Note**: in easy mode it will take defaults for all configs [Check Default Values](https://github.com/deepseek-php/deepseek-php-client/blob/master/src/Enums/Configs/DefaultConfigs.php)

### Advanced Usage

```php
use DeepseekPhp\DeepseekClient;
use DeepseekPhp\Enums\Queries\QueryRoles;
use DeepseekPhp\Enums\Models;

$apiKey = 'your-api-key';

$response = DeepseekClient::build($apiKey, 'https://api.deepseek.com/v2', 500)
    ->query('System setup query', 'system')
    ->query('User input message', 'user')
    ->withModel(Models::CODER->value)
    ->setTemperature(1.5)
    ->run();

echo 'API Response:'.$response;
```

## Use With Frameworks

### [Laravel Deepseek Package](https://github.com/deepseek-php/deepseek-laravel)

### [Symfony Deepseek Package](https://github.com/deepseek-php/deepseek-symfony)

---

## Testing

tests will come soon .

## Changelog

See [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributors ✨

Thanks to these wonderful people for contributing to this project! 💖

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/omaralalwi">
        <img src="https://avatars.githubusercontent.com/u/25439498?v=4" width="50px;" alt="Omar AlAlwi"/>
        <br />
        <sub><b>Omar AlAlwi</b></sub>
      </a>
      <br />
      🏆 Creator
    </td>
    <td align="center">
      <a href="https://github.com/aymanalhattami">
        <img src="https://avatars.githubusercontent.com/u/34315778?v=4" width="50px;" alt="ayman alhattami"/>
        <br />
        <sub><b>ayman alhattami</b></sub>
      </a>
      <br />
      🏆 Contributor
    </td>
    <td align="center">
      <a href="https://github.com/moassaad">
        <img src="https://avatars.githubusercontent.com/u/155223476?v=4" width="50px;" alt="Mohammad Asaad"/>
        <br />
        <sub><b>Mohammad Asaad</b></sub>
      </a>
      <br />
      🏆 Contributor
    </td>
    <td align="center">
      <a href="https://github.com/OpadaAlzaiede">
        <img src="https://avatars.githubusercontent.com/u/48367429?v=4" width="50px;" alt="Opada Alzaiede"/>
        <br />
        <sub><b>Opada Alzaiede</b></sub>
      </a>
      <br />
      🏆 Contributor
    </td>
    <!-- Contributors -->
  </tr>
</table>

Want to contribute? Check out the [contributing guidelines](./CONTRIBUTING.md) and submit a pull request! 🚀

## Security

If you discover any security-related issues, please email creator : `omaralwi2010@gmail.com`.

## License

The MIT License (MIT). See [LICENSE](LICENSE.md) for more information.
