<?php

namespace App\Repositories;

use App\Services\Api\ProductHttpService;
use App\Services\Api\PriceHttpService;
use App\Services\Api\UsersHttpService;
use App\Services\Api\AccountHttpService;
use App\Services\Api\OrdersHttpService;
use Illuminate\Http\Request;
use App\Containers\UserContainer;
use App\Services\Api\ProductAvailableHttpService;
use Cache;
use Log;

class OrderRepository
{
    protected const PREFIX_CACHE_USER = 'user:';

    protected ProductHttpService $productHttpService;
    protected PriceHttpService $priceHttpService;
    protected ProductAvailableHttpService $productAvailableHttpService;
    protected UsersHttpService $usersHttpService;
    protected AccountHttpService $accountHttpService;
    protected OrdersHttpService $ordersHttpService;

    public function __construct(
        ProductHttpService $productHttpService,
        UsersHttpService $usersHttpService,
        AccountHttpService $accountHttpService,
        OrdersHttpService $ordersHttpService,
        PriceHttpService $priceHttpService,
        ProductAvailableHttpService $productAvailableHttpService
    ) {
        $this->productHttpService = $productHttpService;
        $this->priceHttpService = $priceHttpService;
        $this->usersHttpService = $usersHttpService;
        $this->accountHttpService = $accountHttpService;
        $this->ordersHttpService = $ordersHttpService;
        $this->productAvailableHttpService = $productAvailableHttpService;
    }

    public function product($isbn)
    {
        return $this->productHttpService->getProduct($isbn);
    }

    public function price($isbn)
    {
        $response = $this->priceHttpService->getProductPrice($isbn);

        if (empty($response['data'])) {
            return $response;
        }

        $isbns = $response['data']['result']['isbns'];
        $type1 = $response['data']['result']['productType'];

        $prices = [
            $type1 => $this->removeExtraPrices($response['data']['result']['pricing']['exclusive']),
        ];

        $isbn2 = $isbn == $isbns['print'] ? $isbns['ebook'] : $isbns['print'];

        $response = $this->priceHttpService->getProductPrice($isbn2);

        if (empty($response['data'])) {
            return $prices;
        }

        $type2 = $response['data']['result']['productType'];

        return array_merge_recursive($prices, [
            $type2 => $this->removeExtraPrices($response['data']['result']['pricing']['exclusive']),
        ]);
    }

    private function removeExtraPrices($priceList)
    {
        $validPrices = ['USD', 'AUD', 'INR', 'GBP', 'EUR'];
        $prices = [];

        foreach ($validPrices as $v) {
            if (array_key_exists($v, $priceList)) {
                $prices[$v] = "{$priceList[$v]}";
            }
        }
        return $prices;
    }

    public function details($email)
    {
        $user = Cache::get(self::PREFIX_CACHE_USER . $email);
        if (empty($user) || empty($user->userId)) {
            $response = $this->accountHttpService->userId($email);
            if (isset($response['errorCode'])) {
                return $response;
            }

            $details = $this->usersHttpService->getUserDetails($response['userId']);
            if (isset($details['errorCode'])) {
                return $details;
            }

            $user = UserContainer::make(array_merge_recursive($details['data'][0], ['username' => $email]));
            Cache::add(self::PREFIX_CACHE_USER . $email, $user);
        }

        return UserContainer::to_array($user);
    }

    public function placeManualOrder($user, $data)
    {
        return $this->ordersHttpService->placeOrder($user, $data);
    }

    public function getProductAvailability($isbn)
    {
        return $this->productAvailableHttpService->getProductAvailability($isbn);
    }
}
