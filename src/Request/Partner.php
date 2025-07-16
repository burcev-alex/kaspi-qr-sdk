<?php

namespace KaspiQrSdk\Request;

use KaspiQrSdk\Request\AbstractRequest;
use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\Response\DeviceRegisterResponse;
use KaspiQrSdk\Response\TradePointResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

final class Partner extends AbstractRequest
{
    /**
     * @return array<int, TradePointResponse>
     * @throws \Exception
     */
    public function tradePoints(string $companyBin): array
    {
        $url = 'partner/tradepoints';
        if($this->scheme === KaspiScheme::STRONG->value){
            $url .= '/'.$companyBin;
        }
        $httpResponse = $this->makeRequest(
            new Request(
                'GET',
                $this->getBaseUrl($url),
                ['Content-type' => 'application/json']
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (tradePoints)", $httpResponse);
        }

        $result = [];
        foreach($httpResponse['Data'] as $item){
            $result[] = TradePointResponse::fromResponse($item);
        }

        return $result;
    }

	/**
	 * Registers a device with the given identifier and trade point ID.
	 *
	 * @param string $deviceId The unique identifier of the device to be registered.
	 * @param int $tradePointId The ID of the trade point with which the device is associated.
	 * @return DeviceRegisterResponse The response object containing the registration details.
	 */
	public function register(string $deviceId, int $tradePointId): DeviceRegisterResponse
    {
        $data = [
            'DeviceId' => $deviceId,
            'TradePointId' => $tradePointId
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (device/register)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('device/register'),
                ['Content-type' => 'application/json'],
                json_encode($this->getPrepareData($data))
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (device/register)", $httpResponse);
        }

        return DeviceRegisterResponse::fromResponse($httpResponse);
    }

	/**
	 * Deletes a device using the provided device token.
	 *
	 * @param string $deviceToken The device token identifying the device to delete.
	 * @return bool Returns true if the device was successfully deleted.
	 */
	public function delete(string $deviceToken): bool
    {
        $data = [
            'DeviceToken' => $deviceToken
        ];
        if($this->debugMode){
            $this->getLogger()->debug("Request (device/delete)", $data);
        }

        $httpResponse = $this->makeRequest(
            new Request(
                'POST',
                $this->getBaseUrl('device/delete'),
                ['Content-type' => 'application/json'],
                json_encode($data)
            )
        );

        if($this->debugMode){
            $this->getLogger()->debug("Response (device/delete)", $httpResponse);
        }

        return true;
    }
}
