<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use KaspiQrSdk\Config;
use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\KaspiQrClient;

class KaspiQrClientTest extends TestCase
{
    public function testClientCreation(): void
    {
        $config = new Config(
            'ORG_BIN',
            'DEVICE_TOKEN',
            KaspiScheme::EASY,
            'API_KEY',
            'https://qrapi-cert-ip.kaspi.kz'
        );
        $client = new KaspiQrClient($config);
        $this->assertInstanceOf(KaspiQrClient::class, $client);
        $this->assertNotNull($client->merchant);
        $this->assertNotNull($client->partner);
        $this->assertNotNull($client->emulator);
    }
} 