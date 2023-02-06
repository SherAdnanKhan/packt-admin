<?php

namespace App\Services\Api;

class ProductAvailableHttpService extends HttpService
{
    public const PRODUCT_AVAILABLE_API = 'products/%s/summary';

    public function getProductAvailability($isbn)
    {
        $path = sprintf(self::PRODUCT_AVAILABLE_API, $isbn);
        try {
            $response = $this->requestGET($path);

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
