<?php

namespace App\Services\Api;

class OrdersHttpService extends HttpService
{
    protected const ORDER_API_PATH = 'private/users/%s/orders';

    public function placeOrder($user, $data)
    {
        $path = sprintf(self::ORDER_API_PATH, $user['userUuid']);

        try {
            $response = $this->requestPOST($path, $data);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $errorResponse = json_decode(
                $e
                    ->getResponse()
                    ->getBody()
                    ->getContents(),

            );
            return $errorResponse;
        }
    }

    protected function getOptions()
    {
        $token = token();
        return [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'x-api-key' => env('MS_API_KEY')
            ],
        ];
    }
}
