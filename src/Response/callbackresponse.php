<?php

namespace KaspiQrSdk\Response;

use KaspiQrSdk\KaspiSystem;

/**
 * CallbackResponse class provides a structure for handling callback response data.
 *
 * This class encapsulates response data such as status, UUID, amount, and product type.
 * It includes methods for constructing an instance from a response array, retrieving data,
 * modifying certain fields, and converting the instance into an associative array.
 *
 * The primary use of this class is to represent and manipulate response data in a structured format.
 */
final class CallbackResponse
{
	/** @var string|null */
	private ?string $status;
	/** @var string */
	private string $uuid;
	/** @var float|null */
	private ?float $amount;
	/** @var string|null */
	private ?string $productType;

	public function __construct(?string $status, string $uuid, ?float $amount, ?string $productType)
	{
		$this->status = $status;
		$this->uuid = $uuid;
		$this->amount = $amount;
		$this->productType = $productType;
	}

	public static function fromResponse(array $data): self
	{
		$d = $data['Data'];
		return new self(
			$d['Status'] ?? null,
			$d['TransactionId'] ?? '',
			isset($d['Amount']) ? (float)$d['Amount'] : null,
			$data['ProductType'] ?? null
		);
	}

	/** @return string */
	public function getUuid(): string
	{
		return $this->uuid;
	}

	/** @return float|null */
	public function getAmount(): ?float
	{
		return $this->amount;
	}

	/** @return string|null */
	public function getProductType(): ?string
	{
		return $this->productType;
	}

	/** @return string */
	public function getStatus(): string
	{
		return $this->status ?? KaspiSystem::STATUS_CREATED->value;
	}

	public function setAmount(float $price): void
	{
		$this->amount = $price;
	}

	/** @return array */
	public function toArray(): array
	{
		return [
			'status' => $this->getStatus(),
			'uuid' => $this->getUuid(),
			'amount' => $this->getAmount(),
			'productType' => $this->getProductType(),
			'paymentSystem' => 'VISA',
			'dateTime' => date('d.m.Y H:i:s'),
		];
	}
}
