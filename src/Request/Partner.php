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
    public function tradePoints(): array
    {
        $url = 'partner/tradepoints';
        if($this->scheme === KaspiScheme::STRONG->value){
            $companyBin = app('CredentialsDictionary')->getByCode('KASPI_COMPANY_BIN');
            if(!$companyBin){
                $companyBin = env('KASPI_COMPANY_BIN');
            }
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
