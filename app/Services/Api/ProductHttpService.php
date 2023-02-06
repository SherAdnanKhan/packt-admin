<?php

namespace App\Services\Api;

class ProductHttpService extends HttpService
{
    public const PRODUCT_SUMMARY_API = 'products/';

    public function getProduct($isbn)
    {
        try {
            $response = $this->requestGET(self::PRODUCT_SUMMARY_API . $isbn, $this->getReqHeaders());

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $errorResponse = json_decode(
                $e
                    ->getResponse()
                    ->getBody()
                    ->getContents(),
                true
            );
            return $errorResponse;
        }
    }

    private function getReqHeaders()
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . token(),
                'Content-Type' => 'application/json',
            ],
        ];
    }
}
