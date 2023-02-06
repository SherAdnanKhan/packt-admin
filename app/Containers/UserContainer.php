<?php

namespace App\Containers;

class UserContainer extends Container
{
    public string $firstName = '';
    public string $lastName = '';
    public string $username = '';
    public string $userId = '';
    public array $addresses;
    public ?string $netsuiteId = '';
    public string $vat = '';
    public ?string $companyName = '';
    public ?int $discountGroup = 0;
    public ?string $userUuid = '';

    /**
     * @inheritDoc
     */
    public static function make(array $array)
    {
        $c = new static();

        $c->userId = $array['id'];
        foreach ($array as $atr => $value) {
            if (property_exists($c, $atr)) {
                $c->{$atr} = $value;
            }
        }
        return $c;
    }

    public static function to_array($user)
    {
        return [
            'firstName' => $user->firstName ? $user->firstName : null,
            'lastName' => $user->lastName ? $user->lastName : null,
            'username' => $user->username ? $user->username : null,
            'companyName' => $user->companyName ? $user->companyName : null,
            'vat' => $user->vat ? $user->vat : null,
            'netsuiteId' => $user->netsuiteId ? $user->netsuiteId : null,
            'discountGroup' => $user->discountGroup ? $user->discountGroup : null,
            'addresses' => $user->addresses ? $user->addresses : null,
            'userUuid' => $user->userId ? $user->userId : null,
        ];
    }
}
