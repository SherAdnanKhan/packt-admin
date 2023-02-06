<?php

namespace App\Services\Api;

class PriceHttpService extends HttpService
{
    public const PRODUCT_PRICE_API = 'product-price/';

    public function getProductPrice($isbn)
    {
        try {
            $response = $this->requestGET(self::PRODUCT_PRICE_API . $isbn);

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
}
