<?php

namespace KaspiQrSdk\Response;

/**
 * Represents the response received when registering a device.
 *
 * This class encapsulates the token information provided in the registration response.
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
