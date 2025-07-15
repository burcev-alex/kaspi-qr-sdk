<?php

namespace KaspiQrSdk\Response;

/**
 * Ответ с данными торговой точки
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
