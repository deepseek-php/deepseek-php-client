<p align="center">
  <h1 align="center">عميل DeepSeek بلغة PHP</h1>
  <p align="center">🚀 حزمة PHP مفتوحة المصدر ومدعومة من المجتمع للتكامل مع واجهة DeepSeek API</p>
  
  <p align="center">
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/v/deepseek-php/deepseek-php-client" alt="أحدث إصدار">
    </a>
    <a href="https://packagist.org/packages/deepseek-php/deepseek-php-client">
      <img src="https://img.shields.io/packagist/dt/deepseek-php/deepseek-php-client" alt="إجمالي التحميلات">
    </a>
    <a href="https://php.net">
      <img src="https://img.shields.io/badge/PHP-8.1%2B-blue" alt="إصدار PHP">
    </a>
    <a href="LICENSE.md">
      <img src="https://img.shields.io/badge/license-MIT-brightgreen" alt="الترخيص">
    </a>
    <a href="https://github.com/deepseek-php/deepseek-php-client/stargazers">
      <img src="https://img.shields.io/github/stars/deepseek-php/deepseek-php-client?style=social" alt="النجوم على GitHub">
    </a>
  </p>

[الإنجليزية](README.md) | [الصينية](README-CN.md)

## فهرس المحتويات
- [✨ المميزات](#-المميزات)
- [📦 التثبيت](#-التثبيت)
- [🚀 البداية السريعة](#-البداية-السريعة)
  - [الاستخدام الأساسي](#الاستخدام-الأساسي)
  - [التكوين المتقدم](#التكوين-المتقدم)
  - [تحذير هام عند استخدام وضع JSON](#-متطلب-وضع-json-في-deepseek)
  - [الاستخدام مع عميل HTTP من Symfony](#الاستخدام-مع-عميل-http-من-symfony)
  - [الحصول على قائمة النماذج](#الحصول-على-قائمة-النماذج)
  - [استدعاء الدوال](#استدعاء-الدوال)
  - [معلمات التوليد (v2.2.0)](#معلمات-التوليد-v220)
    - [وضع التفكير ومستوى الاستدلال](#وضع-التفكير-ومستوى-الاستدلال)
    - [تسلسلات الإيقاف (stop)](#تسلسلات-الإيقاف-stop)
    - [أخذ العينات بالنواة (top_p)](#أخذ-العينات-بالنواة-top_p)
    - [التحكم باختيار الأداة (tool_choice)](#التحكم-باختيار-الأداة-tool_choice)
    - [الاحتمالات اللوغاريتمية (logprobs)](#الاحتمالات-اللوغاريتمية-logprobs)
    - [معرّف المستخدم النهائي](#معرّف-المستخدم-النهائي)
    - [حقل name على الرسائل](#حقل-name-على-الرسائل)
    - [أدوات الوضع الصارم (strict)](#أدوات-الوضع-الصارم-strict)
  - [تكامل مع الأطر](#-تكامل-مع-الأطر)
- [🆕 دليل الترحيل](#-دليل-الترحيل)
- [📝 سجل التغييرات](#-سجل-التغييرات)
- [🧪 الاختبارات](#-الاختبارات)
- [🔒 الأمان](#-الأمان)
- [📄 الرخصة](#-الرخصة)

---

## ✨ المميزات

- **تكامل API سلس**: واجهة تعتمد على PHP لميزات الذكاء الاصطناعي في DeepSeek.
- **نمط الباني السلس**: أساليب قابلة للسلسلة لبناء الطلبات بطريقة بديهية.
- **جاهز للمؤسسات**: تكامل مع عميل HTTP متوافق مع PSR-18.
- **أحدث نماذج DeepSeek V4**: دعم مباشر لـ `deepseek-v4-pro` و `deepseek-v4-flash` بنافذة سياق تصل إلى مليون رمز ووضعَي التفكير وعدم التفكير.
- **تحكم كامل بمعلمات التوليد (v2.2.0)**: واجهات سلسلة لوضع التفكير، مستوى الاستدلال، تسلسلات الإيقاف، `top_p`، اختيار الأداة (`none` / `auto` / `required` / دالة مسماة)، `logprobs`، معرّف المستخدم النهائي، حقل `name` للرسائل، وأدوات الوضع الصارم.
- **جاهز للبث**: دعم مدمج للتعامل مع الردود في الوقت الفعلي.
- **العديد من عملاء HTTP**: يمكنك استخدام عميل `Guzzle http client` (افتراضي) أو `symfony http client` بسهولة.
- **متوافق مع الأطر**: حزم Laravel و Symfony متاحة.

> **النماذج المدعومة**
>
> - `Models::V4_PRO` — النموذج الرائد، أقصى عدد رموز للإخراج 384K.
> - `Models::V4_FLASH` — سريع وموفّر، أقصى عدد رموز للإخراج 384K.
>
> النماذج القديمة `Models::CHAT`، `Models::CODER`، `Models::R1`، و `Models::R1Zero` مهملة وستتم إزالتها في v3.0.0. الأسماء البديلة `deepseek-chat` و `deepseek-reasoner` ستنسحب من DeepSeek API بتاريخ **2026-07-24**.

---

## 📦 التثبيت

قم بتثبيت الحزمة عبر Composer:

```bash
composer require deepseek-php/deepseek-php-client
```

**المتطلبات**:
- PHP 8.1+

---

## 🚀 البداية السريعة

### الاستخدام الأساسي

ابدأ مع سطرين من الكود فقط:

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->query('Explain quantum computing in simple terms')
    ->run();

echo $response;
```

📌 الإعدادات الافتراضية المستخدمة:
- النموذج: الافتراضي من API (لا يُرسل حقل `model` ما لم تستدعِ `withModel()`).
- الحرارة: 1.3 (`TemperatureValues::GENERAL_CONVERSATION`).
- أقصى عدد رموز: 4096.
- صيغة الاستجابة: `text`.

### التكوين المتقدم

```php
use DeepSeek\DeepSeekClient;
use DeepSeek\Enums\Models;

$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'guzzle');

$response = $client
    ->withModel(Models::V4_PRO->value)
    ->withStream()
    ->setTemperature(1.2)
    ->setMaxTokens(8192)
    ->setResponseFormat('text') // أو "json_object" بحذر.
    ->query('Explain quantum computing in simple terms')
    ->run();

echo 'API Response:'.$response;
```


## ⚠️ متطلب وضع JSON في DeepSeek

عند استخدام:

```php
->setResponseFormat('json_object')
```

يجب أن يحتوي الـ برومبت على **كلمة "json"** بشكل واضح.

وإلا سيتم رفض الطلب من قبل  وترجع رسالة الخطأ التالية:

> `"Prompt must contain the word 'json' in some form to use 'response_format' of type 'json_object'"`

---

### 🚫 استخدام غير صحيح

```php
->setResponseFormat('json_object')
->query('اشرح الحوسبة الكمومية بطريقة مبسطة')
```

### ✅ استخدام صحيح

```php
->setResponseFormat('json_object')
->query('أجب بصيغة JSON صحيحة. اشرح الحوسبة الكمومية بطريقة مبسطة.')
```

> ✅ **نصيحة**: للحصول على أفضل النتائج، قم أيضًا بإعطاء مثال على صيغة JSON في الرسالة.

---

### الاستخدام مع عميل HTTP من Symfony
الحزمة مبنية مسبقاً مع `symfony Http client`، فإذا كنت بحاجة إلى استخدامها مع عميل HTTP الخاص بـ Symfony، فيمكن تحقيق ذلك بسهولة عن طريق تمرير `clientType:'symfony'` إلى دالة `build`.

مثال باستخدام Symfony:

```php
//  مع القيم الافتراضية للـ baseUrl و timeout
$client = DeepSeekClient::build('your-api-key', clientType:'symfony')
// مع التخصيص
$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com', timeout:30, clientType:'symfony');

$client->query('Explain quantum computing in simple terms')
       ->run();
```

### الحصول على قائمة النماذج

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
//     {"id": "deepseek-chat",     "object": "model", "owned_by": "deepseek"},     // مهمل، ينسحب بتاريخ 2026-07-24
//     {"id": "deepseek-reasoner", "object": "model", "owned_by": "deepseek"}      // مهمل، ينسحب بتاريخ 2026-07-24
//   ]
// }
```

###  استدعاء الدوال 

يتيح **استدعاء الدوال** للنموذج استدعاء أدوات خارجية لتعزيز قدراته.
يمكنك الرجوع إلى الوثائق الخاصة باستدعاء الدوال في الملف:
[FUNCTION-CALLING.md](docs/FUNCTION-CALLING.md)

---

### معلمات التوليد (v2.2.0)

منذ الإصدار `v2.2.0` تُتيح الحزمة كامل واجهة معلمات التوليد عبر دوال سلسلة. **كل دالة جديدة اختيارية**: إذا لم تستدعِها، يبقى جسم الطلب مطابقًا حرفيًا لإصدار v2.1.x — لا يوجد ما يستوجب الترحيل.

#### وضع التفكير ومستوى الاستدلال

تدعم نماذج V4 خطوة "تفكير" مخصصة. استخدم [`setThinking()`](src/Traits/Client/HasGenerationParams.php) لتشغيلها أو إيقافها، و [`setReasoningEffort()`](src/Traits/Client/HasGenerationParams.php) لاختيار `high` (الافتراضي للطلبات العادية) أو `max` (موصى به لمسارات الوكلاء).

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

> ⚠️ في وضع التفكير يتجاهل DeepSeek API بصمت كلًّا من `temperature` و `top_p`، كما تُعيد `logprobs` / `top_logprobs` خطأ HTTP 400. انظر [وثائق نموذج الاستدلال](https://api-docs.deepseek.com/guides/reasoning_model).

#### تسلسلات الإيقاف (stop)

ما يصل إلى 16 تسلسلًا. مرّر سلسلة نصية واحدة أو مصفوفة؛ السلاسل المفردة تُحوَّل تلقائيًا إلى مصفوفة من عنصر واحد.

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Write a haiku')
    ->setStop(['###', "\n\n"])
    ->run();
```

#### أخذ العينات بالنواة (top_p)

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Tell me a short story')
    ->setTopP(0.95)
    ->run();
```

#### التحكم باختيار الأداة (tool_choice)

تقبل [`setToolChoice()`](src/Traits/Client/HasGenerationParams.php) القيم `"none"` و `"auto"` و `"required"` (إجبار استدعاء أداة)، أو شكل الدالة المسماة. وضع `"required"` المفقود سابقًا أصبح متاحًا الآن.

```php
use DeepSeek\Enums\Queries\ToolChoiceMode;

// إجبار النموذج على استدعاء أي أداة
$client->setTools($tools)
       ->setToolChoice(ToolChoiceMode::REQUIRED->value);

// إجبار النموذج على استدعاء دالة محددة بالاسم
$client->setTools($tools)
       ->setToolChoice([
           'type' => 'function',
           'function' => ['name' => 'get_weather'],
       ]);
```

#### الاحتمالات اللوغاريتمية (logprobs)

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Hello')
    ->setLogprobs(true)
    ->setTopLogprobs(5)
    ->run();
```

#### معرّف المستخدم النهائي

لعزل حدود المعدل، السلامة، وعزل ذاكرة التخزين المؤقت (KV-cache). يُرسل على السلك باسم حقل `user` وفق مواصفة OpenAI.

```php
$response = DeepSeekClient::build('your-api-key')
    ->setUserId('user-42')
    ->query('Hello')
    ->run();
```

#### حقل name على الرسائل

معامل ثالث اختياري على `query()` (و `buildQuery()`) للتمييز بين المشاركين الذين يحملون نفس الدور وفق مواصفة OpenAI. الاستدعاءات القديمة بمعاملين لا تتأثر.

```php
$response = DeepSeekClient::build('your-api-key')
    ->query('Hello, I am Alice.', 'user', 'alice')
    ->query('Hello, I am Bob.',   'user', 'bob')
    ->run();
```

#### أدوات الوضع الصارم (strict)

تُضيف [`setStrictTool()`](src/Traits/Client/HasToolsFunctionCalling.php) دالة بأداة مع `strict: true`، مما يُلزم النموذج بإنتاج وسائط مطابقة تمامًا لمخطط JSON. تتكامل بأمان مع `setTools()` — تُلحِق ولا تستبدل.

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

### 🛠 تكامل مع الأطر

### [حزمة Deepseek لـ Laravel](https://github.com/deepseek-php/deepseek-laravel)

---

## 🚧 دليل الترحيل

هل تقوم بالترقية من الإصدار v1.x؟ اطلع على دليل الترحيل الشامل الخاص بنا للتغييرات الجذرية وتعليمات الترقية.

---

## 📝 سجل التغييرات

ملاحظات الإصدار التفصيلية متوفرة في [CHANGELOG.md](CHANGELOG.md)

---

## 🧪 الاختبارات

```bash
./vendor/bin/pest
```

تغطية الاختبارات ستتوفر في الإصدار v2.1.

---
<div>

# 🐘✨ **مجتمع DeepSeek PHP** ✨🐘

انقر على الزر أدناه أو [انضم هنا](https://t.me/deepseek_php_community) لتكون جزءًا من مجتمعنا المتنامي!

[![Join Telegram](https://img.shields.io/badge/Join-Telegram-blue?style=for-the-badge&logo=telegram)](https://t.me/deepseek_php_community)


### **هيكل القناة** 🏗️
- 🗨️ **عام** - دردشة يومية
- 💡 **الأفكار والاقتراحات** - تشكيل مستقبل المجتمع
- 📢 **الإعلانات والأخبار** - التحديثات والأخبار الرسمية
- 🚀 **الإصدارات والتحديثات** - تتبع الإصدارات ودعم الترحيل
- 🐞 **المشاكل وتقارير الأخطاء** - حل مشكلات جماعي
- 🤝 **طلبات السحب** - التعاون والمراجعة البرمجية

</div>

---

## 🔒 الأمان

**الإبلاغ عن الثغرات**: إلى [omaralwi2010@gmail.com](mailto:omaralwi2010@gmail.com)

---


## 📄 الرخصة

هذه الحزمة هي برنامج مفتوح المصدر مرخص بموجب [رخصة MIT](LICENSE.md).
