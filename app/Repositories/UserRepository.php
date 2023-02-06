<?php

namespace App\Repositories;

use App\Containers\SubscriptionContainer;
use App\Containers\UserMetadataContainer;
use App\Services\Api\SubscriptionHttpService;
use App\Services\Api\UsersHttpService;
use Illuminate\Support\Facades\Cache;

class UserRepository
{
    public const PREFIX_CACHE_USER = 'user:';
    public const PREFIX_CACHE_SUBSCRIPTION = 'user:subscription:';

    protected SubscriptionHttpService $subscriptionHttpService;
    protected UsersHttpService $usersHttpService;

    public function __construct(SubscriptionHttpService $subscriptionHttpService, UsersHttpService $usersHttpService)
    {
        $this->subscriptionHttpService = $subscriptionHttpService;
        $this->usersHttpService = $usersHttpService;
    }

    public function getUserSubscription($userId)
    {
        /**@var SubscriptionContainer $subscription*/
        $subscription = Cache::get(self::PREFIX_CACHE_SUBSCRIPTION . $userId);
        if (empty($subscription)) {
            $subscription = $this->subscriptionHttpService->getSubscription();
            if ($subscription) {
                $subscription = SubscriptionContainer::make($subscription);
            }
            Cache::add(self::PREFIX_CACHE_SUBSCRIPTION . $userId, $subscription);
        }
        return $subscription;
    }

    /**
     * Get users metadata from user ms
     */
    public function getUserMetaData($userId)
    {
        $user_metadata = Cache::get(self::PREFIX_CACHE_USER . $userId);
        if (empty($user_metadata)) {
            $user_metadata = $this->usersHttpService->getUsersMetadata($userId);
            if ($user_metadata) {
                $user_metadata = UserMetadataContainer::make($user_metadata);
            }

            Cache::add(self::PREFIX_CACHE_USER . $userId, $user_metadata);
        }

        return $user_metadata;
    }
}
