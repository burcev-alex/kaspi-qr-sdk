# Kaspi QR PHP SDK

SDK для интеграции с Kaspi QR API на PHP.

## Установка

```bash
composer require burcev-alex/kaspi-qr-sdk
```

## Быстрый старт

```php
use KaspiQrSdk\Config;
use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\KaspiQrClient;

$config = new Config(
    'ORG_BIN',
    'DEVICE_TOKEN',
    KaspiScheme::EASY,
    'API_KEY',
    'https://qrapi-cert-ip.kaspi.kz'
);
$client = new KaspiQrClient($config);

// Создать инвойс
$invoice = $client->merchant->create('ORDER-123', 1000.0);
```

## Структура
- `src/Config.php` — конфиг SDK
- `src/KaspiQrClient.php` — основной клиент
- `src/Request/` — запросы
- `src/Response/` — ответы
- `src/Exception/` — исключения

## Лицензия
MIT 