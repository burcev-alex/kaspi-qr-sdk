<?php

namespace KaspiQrSdk\Response;

/**
 * Represents a response containing details about an invoice.
 *
 * This class is designed to manage and process the response for an invoice, including payment identifiers,
 * payment methods, and timeout options. It facilitates the creation of instances from raw response data
 * and provides access to various properties of the invoice response.
 */
final class InvoiceResponse
{
    /** @var string */
    private string $uuid;
    /** @var string */
    private string $paymentIdentifier;
    /** @var array */
    private array $paymentMethods;
    /** @var int */
    private int $statusPollingInterval;
    /** @var int */
    private int $linkActivationWaitTimeout;
    /** @var int */
    private int $paymentConfirmationTimeout;

    public function __construct(
        string $uuid,
        string $paymentIdentifier,
        array $paymentMethods,
        int $statusPollingInterval,
        int $linkActivationWaitTimeout,
        int $paymentConfirmationTimeout
    ) {
        $this->uuid = $uuid;
        $this->paymentIdentifier = $paymentIdentifier;
        $this->paymentMethods = $paymentMethods;
        $this->statusPollingInterval = $statusPollingInterval;
        $this->linkActivationWaitTimeout = $linkActivationWaitTimeout;
        $this->paymentConfirmationTimeout = $paymentConfirmationTimeout;
    }

    public static function fromResponse(array $params): self
    {
        $data = $params['Data'];
        $paymentIdentifier = array_key_exists('PaymentLink', $data) ? $data['PaymentLink'] : $data['QrToken'];
        $uuid = array_key_exists('PaymentLink', $data) ? $data['PaymentId'] : $data['QrPaymentId'];
        return new self(
            $uuid,
            $paymentIdentifier,
            array_key_exists('PaymentMethods', $data) ? $data['PaymentMethods'] : [],
            (int)$data['PaymentBehaviorOptions']['StatusPollingInterval'],
            (int)$data['PaymentBehaviorOptions']['LinkActivationWaitTimeout'],
            (int)$data['PaymentBehaviorOptions']['PaymentConfirmationTimeout']
        );
    }

    /** @return bool */
    public function isLink(): bool
    {
        return substr_count($this->paymentIdentifier, 'https') > 0;
    }

    /** @return string */
    public function getId(): string
    {
        return $this->uuid;
    }

    /** @return array */
    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    /** @return string */
    public function getPaymentIdentifier(): string
    {
        return $this->paymentIdentifier;
    }

    /** @return int */
    public function getStatusPollingInterval(): int
    {
        return $this->statusPollingInterval;
    }

    /** @return int */
    public function getLinkActivationWaitTimeout(): int
    {
        return $this->linkActivationWaitTimeout;
    }

    /** @return int */
    public function getPaymentConfirmationTimeout(): int
    {
        return $this->paymentConfirmationTimeout;
    }

    /** @return array */
    public function toArray(): array
    {
        $params = [
            'QrPaymentId' => $this->getId(),
            'PaymentId' => $this->getId(),
            'PaymentMethods' => $this->getPaymentMethods(),
            'PaymentBehaviorOptions' => [
                'StatusPollingInterval' => $this->getStatusPollingInterval(),
                'LinkActivationWaitTimeout' => $this->getLinkActivationWaitTimeout(),
                'PaymentConfirmationTimeout' => $this->getPaymentConfirmationTimeout(),
            ]
        ];

        if($this->isLink()){
            $params['PaymentLink'] = $this->getPaymentIdentifier();
        }else{
            $params['QrToken'] = $this->getPaymentIdentifier();
        }

        return $params;
    }
}
