<?php

namespace KaspiQrSdk\Response;

/**
 * Represents a response containing payment information.
 *
 * This class provides detailed payment-related data, including status,
 * transaction ID, product type, and additional optional metadata such
 * as loan offer, store details, and location information.
 */
final class PaymentInfoResponse
{
    /** @var string */
    private string $status;
    /** @var string */
    private string $transactionId;
    /** @var string */
    private string $productType;
    /** @var float */
    private float $amount;
    /** @var string|null */
    private ?string $loanOfferName;
    /** @var int|null */
    private ?int $loanTerm;
    /** @var string|null */
    private ?string $storeName;
    /** @var string|null */
    private ?string $address;
    /** @var string|null */
    private ?string $city;

    public function __construct(
        string $status,
        string $transactionId,
        string $productType,
        float $amount,
        ?string $loanOfferName = null,
        ?int $loanTerm = null,
        ?string $storeName = null,
        ?string $address = null,
        ?string $city = null
    ) {
        $this->status = $status;
        $this->transactionId = $transactionId;
        $this->productType = $productType;
        $this->amount = $amount;
        $this->loanOfferName = $loanOfferName;
        $this->loanTerm = $loanTerm;
        $this->storeName = $storeName;
        $this->address = $address;
        $this->city = $city;
    }

    public static function fromResponse(array $params): self
    {
        $data = $params['Data'];
        return new self(
            $data['Status'] ?? '',
            $data['TransactionId'] ?? '',
            $data['ProductType'] ?? '',
            isset($data['Amount']) ? (float)$data['Amount'] : 0.0,
            $data['LoanOfferName'] ?? null,
            isset($data['LoanTerm']) ? (int)$data['LoanTerm'] : null,
            $data['StoreName'] ?? null,
            $data['Address'] ?? null,
            $data['City'] ?? null
        );
    }

    /** @return string */
    public function getStatus(): string { return $this->status; }
    /** @return string */
    public function getTransactionId(): string { return $this->transactionId; }
    /** @return string */
    public function getProductType(): string { return $this->productType; }
    /** @return float */
    public function getAmount(): float { return $this->amount; }
    /** @return string|null */
    public function getLoanOfferName(): ?string { return $this->loanOfferName; }
    /** @return int|null */
    public function getLoanTerm(): ?int { return $this->loanTerm; }
    /** @return string|null */
    public function getStoreName(): ?string { return $this->storeName; }
    /** @return string|null */
    public function getAddress(): ?string { return $this->address; }
    /** @return string|null */
    public function getCity(): ?string { return $this->city; }

    /** @return array */
    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'transactionId' => $this->getTransactionId(),
            'productType' => $this->getProductType(),
            'amount' => $this->getAmount(),
            'loanOfferName' => $this->getLoanOfferName(),
            'loanTerm' => $this->getLoanTerm(),
            'storeName' => $this->getStoreName(),
            'address' => $this->getAddress(),
            'city' => $this->getCity(),
        ];
    }
}
