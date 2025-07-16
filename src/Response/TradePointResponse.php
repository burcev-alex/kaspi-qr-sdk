<?php

namespace KaspiQrSdk\Response;

/**
 * Represents a response containing trade point data.
 *
 * This class encapsulates the details of a trade point, including its identifier and name.
 * It provides a constructor for initializing these details and implements a factory method
 * for creating an instance from a given response data array.
 */
final class TradePointResponse
{
    /** @var int */
    private int $id;
    /** @var string */
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function fromResponse(array $data): self
    {
        return new self($data['TradePointId'], $data['TradePointName']);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
