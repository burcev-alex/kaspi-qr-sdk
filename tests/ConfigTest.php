<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use KaspiQrSdk\Config;
use KaspiQrSdk\KaspiScheme;

class ConfigTest extends TestCase
{
    public function testConfigCreationAndGetters(): void
    {
        $config = new Config(
            'ORG_BIN',
            'DEVICE_TOKEN',
            KaspiScheme::EASY,
            'API_KEY',
            'https://qrapi-cert-ip.kaspi.kz'
        );
        $this->assertSame('ORG_BIN', $config->getOrganizationBin());
        $this->assertSame('DEVICE_TOKEN', $config->getDeviceToken());
        $this->assertSame(KaspiScheme::EASY, $config->getScheme());
        $this->assertSame('API_KEY', $config->getApiKey());
        $this->assertSame('https://qrapi-cert-ip.kaspi.kz', $config->getBaseDomain());
    }

    public function testConfigValidation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Config('', '', KaspiScheme::EASY, '', '');
    }
} 