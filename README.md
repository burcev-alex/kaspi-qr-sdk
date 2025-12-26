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
```

## Примеры использования методов

### Подключение с сертификатом

```php
$config = new Config(
    'ORG_BIN',
    'DEVICE_TOKEN',
    KaspiScheme::STRONG,
    'API_KEY',
    'https://qrapi-cert-ip.kaspi.kz'
);
$config->setCaPath('combined_ca.pem');
$config->setCertPath('public.cer');
$config->setKeyPath('private.key');
$config->setKeyPass('asd123456');
$client = new KaspiQrClient($config);
```

### Тестовый режим (отключает валидацию SSL)

```php
$config = new Config(
    'ORG_BIN',
    'DEVICE_TOKEN',
    KaspiScheme::EASY,
    'API_KEY',
    'https://qrapi-cert-ip.kaspi.kz'
);
$config->setTestMode(true);
$client = new KaspiQrClient($config);
```

### Merchant

```php
// Создать инвойс
$invoice = $client->merchant->create('ORDER-123', 1000.0);

// Получить информацию о платеже
$info = $client->merchant->getPaymentInfo($invoice->getId());

// Отменить инвойс
$cancel = $client->merchant->cancel($invoice->getId());

// Возврат платежа
$refund = $client->merchant->refund($invoice->getId(), 500.0);
```

### Partner

```php
// Получить список торговых точек
$tradePoints = $client->partner->tradePoints();

// Зарегистрировать устройство
$register = $client->partner->register('DEVICE_ID', 123456);

// Удалить устройство
$client->partner->delete('DEVICE_TOKEN');
```

### Emulator (только для тестовой среды)

```php
// Проверка доступности API
$ping = $client->emulator->ping();

// Эмулировать сканирование QR
$scan = $client->emulator->scan($invoice->getId());

// Эмулировать подтверждение оплаты
$confirm = $client->emulator->confirm($invoice->getId());

// Эмулировать ошибку сканирования
$scanError = $client->emulator->scanError($invoice->getId());

// Эмулировать ошибку подтверждения
$confirmError = $client->emulator->confirmError($invoice->getId());
```

## Структура
- `src/Config.php` — конфиг SDK
- `src/KaspiQrClient.php` — основной клиент
- `src/Request/` — запросы
- `src/Response/` — ответы
- `src/Exception/` — исключения

## Лицензия
MIT 