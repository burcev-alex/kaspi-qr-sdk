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

final class Merchant extends AbstractRequest
{
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

        return new PaymentInfoResponse($httpResponse);
    }

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
