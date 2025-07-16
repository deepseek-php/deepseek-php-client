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
  - [تكامل مع الأطر](#-تكامل-مع-الأطر)
- [🆕 دليل الترحيل](#-دليل-الترحيل)
- [📝 سجل التغييرات](#-سجل-التغييرات)
- [🧪 الاختبارات](#-الاختبارات)
- [🔒 الأمان](#-الأمان)
- [🤝 المساهمين](#-المساهمين)
- [📄 الرخصة](#-الرخصة)

---

## ✨ المميزات

- **تكامل API سلس**: واجهة تعتمد على PHP لميزات الذكاء الاصطناعي في DeepSeek.
- **نمط الباني السلس**: أساليب قابلة للسلسلة لبناء الطلبات بطريقة بديهية.
- **جاهز للمؤسسات**: تكامل مع عميل HTTP متوافق مع PSR-18.
- **مرونة النماذج**: دعم لعدة نماذج من DeepSeek (Coder, Chat, وغيرها).
- **جاهز للبث**: دعم مدمج للتعامل مع الردود في الوقت الفعلي.
- **العديد من عملاء HTTP**: يمكنك استخدام عميل `Guzzle http client` (افتراضي) أو `symfony http client` بسهولة.
- **متوافق مع الأطر**: حزم Laravel و Symfony متاحة.

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
- النموذج: `deepseek-chat`
-  الحرارة: 0.8

### التكوين المتقدم

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
$client = DeepSeekClient::build(apiKey:'your-api-key', baseUrl:'https://api.deepseek.com/v3', timeout:30, clientType:'symfony');

$client->query('Explain quantum computing in simple terms')
       ->run();
```

### الحصول على قائمة النماذج

```php
use DeepSeek\DeepSeekClient;

$response = DeepSeekClient::build('your-api-key')
    ->getModelsList()
    ->run();

echo $response; // {"object":"list","data":[{"id":"deepseek-chat","object":"model","owned_by":"deepseek"},{"id":"deepseek-reasoner","object":"model","owned_by":"deepseek"}]}
```

###  استدعاء الدوال 

يتيح **استدعاء الدوال** للنموذج استدعاء أدوات خارجية لتعزيز قدراته.
يمكنك الرجوع إلى الوثائق الخاصة باستدعاء الدوال في الملف:
[FUNCTION-CALLING.md](docs/FUNCTION-CALLING.md)

---

هل ترغب في أن أضع النسخ الثلاث (الإنجليزية + العربية + الصينية) ضمن ملف Markdown موحد؟


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

## 🤝 المساهمين

شكراً جزيلاً لهؤلاء الأشخاص المذهلين الذين ساهموا في هذا المشروع! 🎉💖

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/omaralalwi">
        <img src="https://avatars.githubusercontent.com/u/25439498?v=4" width="60px;" style="border-radius:50%;" alt="Omar AlAlwi"/>
        <br />
        <b>Omar AlAlwi</b>
      </a>
      <br />
      🏆 المُنشئ
    </td>
    <td align="center">
      <a href="https://github.com/aymanalhattami">
        <img src="https://avatars.githubusercontent.com/u/34315778?v=4" width="60px;" style="border-radius:50%;" alt="Ayman Alhattami"/>
        <br />
        <b>Ayman Alhattami</b>
      </a>
      <br />
      ⭐ مساهم
    </td>
    <td align="center">
      <a href="https://github.com/moassaad">
        <img src="https://avatars.githubusercontent.com/u/155223476?v=4" width="60px;" style="border-radius:50%;" alt="Mohammad Asaad"/>
        <br />
        <b>Mohammad Asaad</b>
      </a>
      <br />
      ⭐ مساهم
    </td>
    <td align="center">
      <a href="https://github.com/OpadaAlzaiede">
        <img src="https://avatars.githubusercontent.com/u/48367429?v=4" width="60px;" style="border-radius:50%;" alt="Opada Alzaiede"/>
        <br />
        <b>Opada Alzaiede</b>
      </a>
      <br />
      ⭐ مساهم
    </td>
    <td align="center">
      <a href="https://github.com/hishamco">
        <img src="https://avatars.githubusercontent.com/u/3237266?v=4" width="60px;" style="border-radius:50%;" alt="Hisham Bin Ateya"/>
        <br />
        <b>Hisham Bin Ateya</b>
      </a>
      <br />
      ⭐ مساهم
    </td>
    <td align="center">
      <a href="https://github.com/VinchanGit">
        <img src="https://avatars.githubusercontent.com/u/38177046?v=4" width="60px;" style="border-radius:50%;" alt="Vinchan"/>
        <br />
        <b>Vinchan</b>
      </a>
      <br />
      ⭐ مساهم
    </td>
  </tr>
</table>

**هل ترغب في المساهمة؟** اطلع على [إرشادات المساهمة](./CONTRIBUTING.md) وقدم طلب سحب! 🚀

---

## 📄 الرخصة

هذه الحزمة هي برنامج مفتوح المصدر مرخص بموجب [رخصة MIT](LICENSE.md).
