<?php

namespace KaspiQrSdk\Request;

use KaspiQrSdk\Request\AbstractRequest;
use KaspiQrSdk\KaspiScheme;
use GuzzleHttp\Psr7\Request;

/**
 * The Emulator class provides methods to simulate HTTP requests for
 * different endpoints related to payment operations and health checks.
 * This class is designed to interact with a predefined API to perform
 * actions such as ping, scan, confirm, and error handling.
 *
 * It extends the functionality of the AbstractRequest class.
 */
final class Emulator extends AbstractRequest
{
	/**
	 * Sends a GET request to the health ping endpoint to check system availability.
	 *
	 * @return array The response from the health ping endpoint.
	 */
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

	/**
	 * Sends a POST request to the payment scan endpoint with the provided invoice ID.
	 *
	 * @param string $invoiceId The ID of the invoice to be processed for scanning.
	 * @return array The response from the payment scan endpoint.
	 */
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

	/**
	 * Confirms a payment by sending a request with the provided invoice ID.
	 *
	 * @param string $invoiceId The unique identifier of the invoice to confirm the payment.
	 * @return array The response received from the payment confirmation request.
	 */
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

	/**
	 * Sends a POST request to the payment scan error endpoint with the provided invoice ID.
	 *
	 * @param string $invoiceId The ID of the invoice to be used for the scan error process.
	 * @return array The response from the payment scan error endpoint.
	 */
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

	/**
	 * Sends a POST request to the payment confirm error endpoint with the provided invoice ID.
	 *
	 * @param string $invoiceId The ID of the invoice related to the error being confirmed.
	 * @return array The response from the payment confirm error endpoint.
	 */
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
