<?php

namespace App\Services\Api;

class AccountHttpService extends HttpService
{
    public const USERID_API_PATH = 'users/username/';
    public const LOGIN_API_PATH = 'users/tokens';
    public const REGISTER_API_PATH = 'users';

    public function userId($email)
    {
        try {
            $response = $this->requestGET(self::USERID_API_PATH . strtolower($email), $this->getReqHeaders());

            $json_response = json_decode($response->getBody()->getContents(), true);

            return [
                'httpStatus' => $json_response['httpStatus'],
                'userId' => $json_response['data']['id'],
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
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

    public function login($username, $password)
    {
        try {
            $response = $this->requestPOST(self::LOGIN_API_PATH, [
                'username' => $username,
                'password' => $password,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return ['error' => 'invalid credentials'];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function register($username, $password, $passwordConfirmation, $recaptcha)
    {
        try {
            $response = $this->requestPOST(self::REGISTER_API_PATH, [
                'username' => $username,
                'password' => $password,
                'passwordConfirmation' => $passwordConfirmation,
                'recaptcha' => $recaptcha,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
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
