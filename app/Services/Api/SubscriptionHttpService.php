<?php

namespace App\Services\Api;

class SubscriptionHttpService extends HttpService
{
    public function getSubscription()
    {
        try {
            $response = $this->requestGET();
        } catch (\Exception $e) {
            return null;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getOptions()
    {
        $token = token();
        return [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ];
    }
}
