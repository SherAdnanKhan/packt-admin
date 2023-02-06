<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use App\Services\Api\AccountHttpService;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\User;

class jwtUserProvider implements UserProvider
{
    /**
     * The Mongo User Model
     */
    private User $model;
    private UserRepository $userRepository;
    private AccountHttpService $accountHttpService;

    /**
     * Create a new mongo user provider.
     *
     * @param User $user
     * @param UserRepository $userRepository
     * @param AccountHttpService $accountHttpService
     */
    public function __construct(User $user, UserRepository $userRepository, AccountHttpService $accountHttpService)
    {
        $this->model = $user;
        $this->userRepository = $userRepository;
        $this->accountHttpService = $accountHttpService;
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Retrieve a user by the given credentials username and password
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        $response = $this->accountHttpService->login($credentials['username'], $credentials['password']);
        if (isset($response['data']['access'])) {
            set_tokens($response['data']);
            //            $this->model->subscription = $this->userRepository->getUserSubscription();
            return $this->model;
        }

        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable $user
     * @param  array  $credentials  Request credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return md5($credentials['password']) == $user->getAuthPassword();
    }

    /**
     * Retrieve a user by the given ID from external API
     * @param mixed $identifier user ID
     * @return User|Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        /**@todo Make API request to fetch user info and store in cache*/
        $payload = auth()->payload();
        $this->model->userId = $payload['userId'];
        $this->model->name = $payload['username'];
        $this->model->subscription = $this->userRepository->getUserSubscription($this->model->userId);
        $this->model->userMetadata = $this->userRepository->getUserMetaData($this->model->userId);
        return $this->model;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }
}
