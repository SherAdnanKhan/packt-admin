<?php

namespace App\Services\Api;

use Illuminate\Http\Request;

class UsersHttpService extends HttpService
{
    public function getUsersMetadata($userId)
    {
        // $userId could be 'me'
        $response = $this->requestGET("users/{$userId}/metadata");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserDetails($userId)
    {
        $path = 'users/' . $userId;

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

    public function putUser($userId, $userDetails)
    {
        $path = "users/$userId";

        try {
            $response = $this->requestPUT($path, $userDetails);
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

    public function registerAddressAsDefault($id, $type, $userId)
    {
        $path = "users/$userId/addresses/$id?type=$type";

        try {
            $response = $this->requestPOST($path);
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

    public function createAddress($address, $userId)
    {
        $path = "users/$userId/addresses";

        try {
            $response = $this->requestPOST($path, $address);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception $e) {
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
