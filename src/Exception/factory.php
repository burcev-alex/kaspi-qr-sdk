<?php

namespace KaspiQrSdk\Exception;

use KaspiQrSdk\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface;

/**
 * Factory class used for creating exceptions based on the response from the ResponseHandler.
 *
 * This class processes the status code and the response content to determine the appropriate exception instance to return.
 */
final class Factory
{
    public static function createFromResponse(ResponseHandler $response)
    {
        $error = json_decode($response->getContents(), true);

        switch($response->getStatusCode()){
            case 400:
                $strMessage = array_key_exists('message', $error)
                    ? $error['message']
                    : json_encode($error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $response = "Ошибка запроса: ".$strMessage;
                $exception = new InvalidRequestException($response);
                break;
            case 401:
                $exception = new AuthorisationException("Authorization error");
                break;
            case 500:
                $exception = new InternalErrorException();
                break;
            case 503:
                $exception = new ServiceUnavailableException("Some services are unavailable");
                break;
            default:
                $strMessage = is_array($error)
                    ? implode(' / ', $error)
                    : $response->getStatusCode().($response->getContents() ? ' / '.$response->getContents() : '');
                $exception = new KaspiSdkException($strMessage);
                break;
        }

        return $exception;
    }
}
