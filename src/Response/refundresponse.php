<?php

namespace KaspiQrSdk\Response;

/**
 * Represents the response of a refund operation.
 *
 * This class contains information about the operation ID of a refund process, which is set during instantiation.
 * It provides functionality to initialize itself from a given response array and retrieve the stored operation ID.
 */
final class RefundResponse
{
    /** @var int */
    private int $operationId;

    public function __construct(int $id)
    {
        $this->operationId = $id;
    }

    public static function fromResponse(array $data): self
    {
        return new self((int)$data['Data']['ReturnOperationId']);
    }

    /**
     * @return int
     */
    public function getOperationId(): int
    {
        return $this->operationId;
    }
}
