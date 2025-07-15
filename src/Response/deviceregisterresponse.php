<?php

namespace KaspiQrSdk\Response;

/**
 * Ответ на регистрацию устройства
 */
final class DeviceRegisterResponse
{
    /** @var string */
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public static function fromResponse(array $data): self
    {
        return new self($data['Data']['DeviceToken']);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
