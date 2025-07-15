<?php

namespace KaspiQrSdk;

use Psr\Log\LoggerInterface;

/**
 * Конфиг для Kaspi QR SDK
 */
class Config
{
    private string $organizationBin;
    private string $deviceToken;
    private KaspiScheme $scheme;
    private string $apiKey;
    private string $baseDomain;
    private ?string $caPath = null;
    private ?string $certPath = null;
    private ?string $keyPath = null;
    private ?string $keyPass = null;
    private ?LoggerInterface $logger = null;

    /**
     * @param string $organizationBin
     * @param string $deviceToken
     * @param KaspiScheme $scheme
     * @param string $apiKey
     * @param string $baseDomain
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $organizationBin,
        string $deviceToken,
        KaspiScheme $scheme,
        string $apiKey,
        string $baseDomain
    ) {
        if (empty($organizationBin) || empty($deviceToken) || empty($apiKey) || empty($baseDomain)) {
            throw new \InvalidArgumentException('organizationBin, deviceToken, apiKey, baseDomain are required');
        }
        $this->organizationBin = $organizationBin;
        $this->deviceToken = $deviceToken;
        $this->scheme = $scheme;
        $this->apiKey = $apiKey;
        $this->baseDomain = $baseDomain;
    }

    /** @return string */
    public function getOrganizationBin(): string { return $this->organizationBin; }
    /** @param string $v */
    public function setOrganizationBin(string $v): void { $this->organizationBin = $v; }

    /** @return string */
    public function getDeviceToken(): string { return $this->deviceToken; }
    /** @param string $v */
    public function setDeviceToken(string $v): void { $this->deviceToken = $v; }

    /** @return KaspiScheme */
    public function getScheme(): KaspiScheme { return $this->scheme; }
    /** @param KaspiScheme $v */
    public function setScheme(KaspiScheme $v): void { $this->scheme = $v; }

    /** @return string */
    public function getApiKey(): string { return $this->apiKey; }
    /** @param string $v */
    public function setApiKey(string $v): void { $this->apiKey = $v; }

    /** @return string */
    public function getBaseDomain(): string { return $this->baseDomain; }
    /** @param string $v */
    public function setBaseDomain(string $v): void { $this->baseDomain = $v; }

    /** @return string|null */
    public function getCaPath(): ?string { return $this->caPath; }
    /** @param string|null $v */
    public function setCaPath(?string $v): void { $this->caPath = $v; }

    /** @return string|null */
    public function getCertPath(): ?string { return $this->certPath; }
    /** @param string|null $v */
    public function setCertPath(?string $v): void { $this->certPath = $v; }

    /** @return string|null */
    public function getKeyPath(): ?string { return $this->keyPath; }
    /** @param string|null $v */
    public function setKeyPath(?string $v): void { $this->keyPath = $v; }

    /** @return string|null */
    public function getKeyPass(): ?string { return $this->keyPass; }
    /** @param string|null $v */
    public function setKeyPass(?string $v): void { $this->keyPass = $v; }

    /** @return LoggerInterface|null */
    public function getLogger(): ?LoggerInterface { return $this->logger; }
    /** @param LoggerInterface|null $v */
    public function setLogger(?LoggerInterface $v): void { $this->logger = $v; }
} 