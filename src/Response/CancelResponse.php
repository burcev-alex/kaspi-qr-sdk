<?php

namespace KaspiQrSdk\Response;

/**
 * Represents the response of a cancel action.
 *
 * This class encapsulates the status of the response,
 * providing methods to initialize and retrieve the cancel response details.
 */
final class CancelResponse
{
	/** @var string */
	private string $status;

	public function __construct(string $status)
	{
		$this->status = $status;
	}

	public static function fromResponse(array $data): self
	{
		return new self($data['Data']['Status']);
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}
}
