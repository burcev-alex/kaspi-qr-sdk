<?php

namespace KaspiQrSdk\Request;

use KaspiQrSdk\Request\AbstractRequest;
use KaspiQrSdk\KaspiScheme;
use GuzzleHttp\Psr7\Request;

final class Emulator extends AbstractRequest
{
    public function ping(): array
    {
        $httpResponse = $this->makeRequest(
            new Request(
                'GET',
                $this->getBaseUrl('health/ping'),
                ['Content-type' => 'application/json']
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (health/ping)", $httpResponse);
        }

        return $httpResponse;
    }

    public function scan(string $invoiceId): array
    {
        $data = [
            'qrPaymentId' => $invoiceId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (payment/scan)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('test/payment/scan'),
                ['Content-type' => 'application/json'],
                json_encode($data)
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (payment/scan)", $httpResponse);
        }

        return $httpResponse;
    }

    public function confirm(string $invoiceId): array
    {
        $data = [
            'qrPaymentId' => $invoiceId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (payment/confirm)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('test/payment/confirm'),
                ['Content-type' => 'application/json'],
                json_encode($data)
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (payment/confirm)", $httpResponse);
        }

        return $httpResponse;
    }

    public function scanError(string $invoiceId): array
    {
        $data = [
            'qrPaymentId' => $invoiceId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (payment/scanerror)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('test/payment/scanerror'),
                ['Content-type' => 'application/json'],
                json_encode($data)
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (payment/scanerror)", $httpResponse);
        }

        return $httpResponse;
    }

    public function confirmError(string $invoiceId): array
    {
        $data = [
            'qrPaymentId' => $invoiceId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (payment/confirmerror)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('test/payment/confirmerror'),
                ['Content-type' => 'application/json'],
                json_encode($data)
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (payment/confirmerror)", $httpResponse);
        }

        return $httpResponse;
    }
}
