<?php

namespace App\Repositories;

use App\Services\Api\AccountHttpService;
use App\Services\Api\UsersHttpService;
use App\Services\Api\ProductHttpService;
use Illuminate\Http\Request;

class AccountRepository
{
    protected AccountHttpService $accountHttpService;
    protected UsersHttpService $usersHttpService;
    protected ProductHttpService $productHttpService;

    public function __construct(
        AccountHttpService $accountHttpService,
        UsersHttpService $usersHttpService,
        ProductHttpService $productHttpService
    ) {
        $this->accountHttpService = $accountHttpService;
        $this->usersHttpService = $usersHttpService;
        $this->productHttpService = $productHttpService;
    }

    public function userId($email)
    {
        return $this->accountHttpService->userId($email);
    }

    public function product($isbn)
    {
        return $this->productHttpService->getProduct($isbn);
    }

    public function userLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $response = $this->accountHttpService->login($username, $password);

        return $response;
    }

    public function authRegisterUser($username, $password, $passwordConfirmation, $recaptcha)
    {
        return $this->accountHttpService->register($username, $password, $passwordConfirmation, $recaptcha);
    }

    public function createUserAddresses($addresses, $userId)
    {
        $shipResponse = $this->usersHttpService->createAddress($addresses['shipping'], $userId);
        $billResponse = $this->usersHttpService->createAddress($addresses['billing'], $userId);

        $shipID = isset($shipResponse['errorCode']) ? null : $shipResponse['data'][0]['id'];
        $billID = isset($billResponse['errorCode']) ? null : $billResponse['data'][0]['id'];

        if (!$shipID) {
            $shipResponse['errorCode'] =
                $shipResponse['errorCode'] . ' while attempting to register new shipping address.';
            return $shipResponse;
        } elseif (!$billID) {
            $billResponse['errorCode'] =
                $billResponse['errorCode'] . ' while attempting to register new billing address.';
            return $billResponse;
        }

        $shipDefaultResponse = $this->usersHttpService->registerAddressAsDefault($shipID, 'shipping', $userId);
        $billDefaultResponse = $this->usersHttpService->registerAddressAsDefault($billID, 'billing', $userId);

        if (isset($shipDefaultResponse['errorCode'])) {
            return $shipResponse;
        } elseif (isset($billDefaultResponse['errorCode'])) {
            return $billResponse;
        }
    }

    public function updateUser($userId, $userDetails)
    {
        return $this->usersHttpService->putUser($userId, $userDetails);
    }

    public function setToken($response)
    {
        return set_tokens($response['data']);
    }
}
