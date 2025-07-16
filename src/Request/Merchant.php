<?php

namespace KaspiQrSdk\Request;

use KaspiQrSdk\Request\AbstractRequest;
use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\Response\CancelResponse;
use KaspiQrSdk\Response\InvoiceResponse;
use KaspiQrSdk\Response\PaymentInfoResponse;
use KaspiQrSdk\Response\RefundResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

/**
 * The Merchant class handles requests related to QR payments, including the creation
 * of invoices, retrieval of payment information, cancellation of payments, and processing refunds.
 * This class extends AbstractRequest and utilizes reusable functionalities for making HTTP requests.
 */
final class Merchant extends AbstractRequest
{
	/**
	 * Creates an invoice request and returns the corresponding response.
	 *
	 * @param string $accountNumber The account number associated with the invoice.
	 * @param float $price The amount for the invoice.
	 * @param bool $isMobile Indicates whether the request is for a mobile-specific URL. Defaults to false.
	 * @return InvoiceResponse The response object containing the details of the created invoice.
	 */
	public function create(string $accountNumber, float $price, bool $isMobile = false): InvoiceResponse
    {
        $data = [
            'DeviceToken' => $this->getDeviceToken(),
            'Amount' => $price,
            'ExternalId' => $accountNumber
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (qr/create)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl($isMobile ? 'qr/create-link' : 'qr/create'),
                ['Content-type' => 'application/json'],
                json_encode($this->getPrepareData($data))
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (qr/create)", $httpResponse);
        }

        return InvoiceResponse::fromResponse($httpResponse);
    }

	/**
	 * Retrieves payment information for a given payment identifier.
	 *
	 * @param string $uuid The unique identifier of the payment whose details are to be retrieved.
	 * @return PaymentInfoResponse An object containing the details of the payment.
	 */
	public function getPaymentInfo(string $uuid): PaymentInfoResponse
    {
        if($this->debugMode){
            $this->getLogger()->debug("Request (getPaymentInfo)", ['qrPaymentId' => $uuid]);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'GET',
                $this->getBaseUrl('payment/status/'.$uuid),
                ['Content-type' => 'application/json']
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (getPaymentInfo)", $httpResponse);
        }

        return PaymentInfoResponse::fromResponse($httpResponse);
    }

	/**
	 * Cancels a payment associated with the specified invoice identifier.
	 *
	 * @param string $invoiceId The unique identifier of the invoice to be canceled.
	 * @return CancelResponse An object representing the response of the cancel operation.
	 */
	public function cancel(string $invoiceId): CancelResponse
    {
        $data = [
            'DeviceToken' => $this->getDeviceToken(),
            'QrPaymentId' => $invoiceId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (remote/cancel)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('remote/cancel'),
                ['Content-type' => 'application/json'],
                json_encode($this->getPrepareData($data))
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (remote/cancel)", $httpResponse);
        }

        return CancelResponse::fromResponse($httpResponse);
    }

	/**
	 * Initiates a refund process for a given invoice with a specified amount.
	 *
	 * @param string $invoiceId The unique identifier of the invoice to be refunded.
	 * @param float $price The amount to be refunded for the specified invoice.
	 * @return RefundResponse An object containing the response of the refund operation.
	 */
	public function refund(string $invoiceId, float $price): RefundResponse
    {
        $data = [
            'DeviceToken' => $this->getDeviceToken(),
            'QrPaymentId' => $invoiceId,
            'Amount' => $price,
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (payment/return)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('payment/return'),
                ['Content-type' => 'application/json'],
                json_encode($this->getPrepareData($data))
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (payment/return)", $httpResponse);
        }

        return RefundResponse::fromResponse($httpResponse);
    }

    private function getDeviceToken(): ?string
    {
        if(!is_null($this->deviceToken)){
            return $this->deviceToken;
        }

        $token = app('CredentialsDictionary')->getByCode('KASPI_DEVICE_TOKEN', app('System')->siteId());
        if(!$token){
            $token = env('KASPI_DEVICE_TOKEN');
        }

        return $token ?? null;
    }
}
