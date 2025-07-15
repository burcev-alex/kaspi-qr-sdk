<?php

namespace KaspiQrSdk\Response;

/**
 * Ответ на отмену платежа
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
