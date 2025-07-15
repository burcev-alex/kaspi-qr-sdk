<?php

namespace KaspiQrSdk\Response;

/**
 * Ответ на возврат платежа
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
