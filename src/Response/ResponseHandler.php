<?php

namespace KaspiQrSdk\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Handles HTTP Response processing and provides utility methods
 * to retrieve status code and response contents.
 */
class ResponseHandler
{
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    private ?ResponseInterface $response;

    public function __construct(?ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->response?->getStatusCode() ?? self::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getContents(): string
    {
        if(!is_null($this->response)){
            $contents = $this->response->getBody()->getContents();
            $this->response->getBody()->rewind();
            return $contents;
        }else{
            return '';
        }
    }
}

