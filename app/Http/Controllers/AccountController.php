<?php

namespace App\Http\Controllers;

use Validator;
use App\Model\Country;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\ViewService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Repositories\AccountRepository;
use App\Http\Requests\CreateAccountRequest;

class AccountController extends BaseController
{
    protected AccountRepository $accountRepo;
    protected ViewService $viewService;

    public function __construct(AccountRepository $auth)
    {
        $this->accountRepo = $auth;
        $view = 'pages.new_account';
        $this->viewService = new ViewService($view);
    }

    public function index()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name->value', 'desc')
            ->get();

        $collectiondata = json_decode($countries, true);

        $collection = collect($collectiondata);

        $sorted = $collection->sortBy(function ($product, $key) {
            return $product['name']['en'];
        });

        $sortedcountries = [];

        $index = 0;
        foreach ($sorted as $sort) {
            $sortedcountries[$index] = $sort;

            $index++;
        }
        return view('pages.new_account')->with('countries', $sortedcountries);
    }

    public function register(CreateAccountRequest $request)
    {
        try {
            $userAccount = $this->createUserAccount($request);
            if ($userAccount) {
                return $userAccount;
            }
            return $this->sendResponse([], 'Account Created');
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage());
        }
    }

    private function createUserAccount($request)
    {
        $username = $request->input('email');
        $password = $request->input('password');
        $passwordConfirmation = $request->input('passwordConfirmation');
        $recaptcha = $request->input('g-recaptcha-response');

        try {
            $authResponse = $this->accountRepo->authRegisterUser(
                $username,
                $password,
                $passwordConfirmation,
                $recaptcha
            );

            if (
                isset($authResponse['errorCode']) &&
                isset($authResponse['message']) &&
                $authResponse['errorCode'] === 7000006
            ) {
                return $this->sendError($authResponse['message'], $authResponse['errorCode']);
            }

            if (isset($authResponse['errorCode']) && isset($authResponse['message']) && $authResponse['errorCode']) {
                return $this->sendError($authResponse['message'], $authResponse['errorCode']);
            }

            if (isset($authResponse['data']['access']) && isset($authResponse['message'])) {
                $response = $this->updateUserDetails($authResponse, $request);
                if (isset($response['errorCode']) && isset($authResponse['message'])) {
                    return $this->sendError($authResponse['message'], $authResponse['errorCode']);
                }
            }
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    private function updateUserDetails($response, $request)
    {
        $userId = $response['data']['userId'];

        $userDetails = [
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'companyName' => $request->input('companyName'),
            'vat' => $request->input('vat'),
            'discountGroup' => $request->input('discountGroup'),
            'netsuiteId' => $request->input('netsuiteId'),
        ];

        $updateResponse = $this->accountRepo->updateUser($userId, $userDetails);

        if (isset($updateResponse['message'])) {
            return $updateResponse;
        }

        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $billCountryCode = Country::find($request->input('bill_country'));

        $bill_address = [
            'contactName' => "$firstName $lastName",
            'line1' => $request->input('bill_line1'),
            'line2' => $request->input('bill_line2'),
            'city' => $request->input('bill_city'),
            'state' => $request->input('bill_state') ? $request->input('bill_state') : '',
            'postalCode' => $request->input('bill_postalCode'),
            'country' => $billCountryCode->code_alpha3,
            'telephone' => $request->input('bill_telephone'),
        ];

        if ($request->input('billAsShip')) {
            $ship_address = $bill_address;
        } else {
            $billCountryCode = Country::find($request->input('ship_country'));
            $ship_address = [
                'contactName' => "$firstName $lastName",
                'line1' => $request->input('ship_line1'),
                'line2' => $request->input('ship_line2'),
                'city' => $request->input('ship_city'),
                'state' => $request->input('ship_state') ? $request->input('ship_state') : '',
                'postalCode' => $request->input('ship_postalCode'),
                'country' => $billCountryCode->code_alpha3,
                'telephone' => $request->input('ship_telephone'),
            ];
        }

        $addresses = [
            'shipping' => $ship_address,
            'billing' => $bill_address,
        ];

        $addressResponse = $this->accountRepo->createUserAddresses($addresses, $userId);

        if (isset($addressResponse['errorCode']) && isset($addressResponse['message'])) {
            throw new \Exception($addressResponse['message']);
        }
    }
}
