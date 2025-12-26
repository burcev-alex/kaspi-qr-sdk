<?php

namespace KaspiQrSdk;

use GuzzleHttp\Client;
use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\Request\Merchant;
use KaspiQrSdk\Request\Partner;
use KaspiQrSdk\Request\Emulator;
use Psr\Log\LoggerInterface;
use KaspiQrSdk\Config;

final class KaspiQrClient
{
	public Partner $partner;
	public Merchant $merchant;
	public Emulator $emulator;

	private string $scheme;
	private string $version = 'v01';
	private int $port;
	private array $schemeOptions = [
		'r1' => ['scheme' => 'r1', 'port' => 8543],
		'r2' => ['scheme' => 'r2', 'port' => 8544],
		'r3' => ['scheme' => 'r3', 'port' => 8545],
	];

	public function __construct(Config $config)
	{
		$this->scheme = $config->getScheme()->value;
		$this->port = $this->schemeOptions[$config->getScheme()->value]['port'];
		$client = new Client(array_merge([
			'base_uri' => $this->collectUrl($config->getBaseDomain()),
			'headers' => $this->getHeaders($config->getApiKey()),
		],
			$this->getSslCertificate(
				$config->getCaPath(),
				$config->getCertPath(),
				$config->getKeyPath(),
				$config->getKeyPass(),
				$config->isTestMode()
			)));
		$this->merchant = new Merchant(
			$client,
			$this->collectUrl($config->getBaseDomain()),
			$this->scheme,
			$config->getOrganizationBin(),
			$config->getDeviceToken(),
			$config->getLogger()
		);
		$this->partner = new Partner(
			$client,
			$this->collectUrl($config->getBaseDomain()),
			$this->scheme,
			$config->getOrganizationBin(),
			$config->getDeviceToken(),
			$config->getLogger()
		);
		$this->emulator = new Emulator(
			$client,
			$this->collectUrl($config->getBaseDomain()),
			$this->scheme,
			$config->getOrganizationBin(),
			$config->getDeviceToken(),
			$config->getLogger()
		);
	}

	protected function getHeaders(string $apiKey): array
	{
		return [
			'X-Request-ID' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
			'Api-Key' => $apiKey,
		];
	}

	protected function getSslCertificate(?string $caPath, ?string $certPath, ?string $keyPath, ?string $keyPass, bool $testMode = false): array
	{
		if ($this->scheme !== KaspiScheme::STRONG->value) {
			return $testMode ? ['verify' => false] : [];
		}
		if (!$caPath || !$certPath || !$keyPath) {
			throw new \Exception("CA/cert/key path required for STRONG scheme");
		}
		return [
			'timeout' => 10,
			'cert' => [$certPath, $keyPass],
			'ssl_key' => [$keyPath, $keyPass],
			'verify' => $testMode ? false : $caPath,
		];
	}

	protected function collectUrl(string $baseDomain): string
	{
		$params = [
			$baseDomain . ':' . $this->port,
			$this->scheme,
			$this->version,
		];
		return implode("/", $params);
	}
}
