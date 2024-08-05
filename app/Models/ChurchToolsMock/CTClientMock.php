<?php

namespace App\Models\ChurchToolsMock;

use CTApi\CTClient;
use CTApi\Utils\CTMessageBody;
use CTApi\Utils\CTResponse;
use Psr\Http\Message\ResponseInterface;

class CTClientMock extends CTClient
{
    public function get($uri, array $options = []): ResponseInterface
    {
        return ($this->convertRequestToResponse($uri, $options));
    }

    public function post($uri, array $options = []): ResponseInterface
    {
        return ($this->convertRequestToResponse($uri, $options, "POST"));
    }

    public function patch($uri, array $options = []): ResponseInterface
    {
        return ($this->convertRequestToResponse($uri, $options, "PATCH"));
    }

    public function put($uri, array $options = []): ResponseInterface
    {
        return ($this->convertRequestToResponse($uri, $options, "PUT"));
    }

    public function delete($uri, array $options = []): ResponseInterface
    {
         return CTResponse::createEmpty();
    }

    protected function convertRequestToResponse($uri, $options, string $method = "GET"): ResponseInterface
    {
        $responseData = HttpMockDataResolver::resolveEndpoint($uri, $options, $method);

        $ctResponse = CTResponse::createEmpty();
        $ctResponse->withBody(new CTMessageBody($responseData));

        return $ctResponse;
    }

}
