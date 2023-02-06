<?php

namespace App\Containers;

class SubscriptionContainer extends Container
{
    public const PRICING_MODEL_ANNUAL = 'Annual';
    public const PRICING_MODEL_MONTH = 'Month';
    public const PRICING_MODEL_EIGHTEEN_MONTHS = 'Eighteen_Months';

    public const SUBSCRIPTION_STATUS_ACTIVE = 'active';
    public const SUBSCRIPTION_STATUS_PAUSED = 'paused';
    public const SUBSCRIPTION_STATUS_CANCELLED = 'cancelled';

    public const STATUS_ACTIVE = true;
    public const STATUS_NOT_ACTIVE = false;

    public string $status;
    public string $subscriptionStatus;
    public string $pricingModel;
    public string $renewalDate;

    /**
     * @inheritDoc
     */
    public static function make(array $array)
    {
        $c = new static();
        if (!isset($array['data'][0])) {
            return $c;
        }
        $data = $array['data'][0];

        if (isset($data['renewalDate'])) {
            $c->renewalDate = $data['renewalDate'];
        }

        if (isset($data['subscriptionStatus'])) {
            $c->subscriptionStatus = $data['subscriptionStatus'];
        }

        if (isset($data['pricing']['pricingModel'])) {
            $c->pricingModel = $data['pricing']['pricingModel'];
        }

        if ($array['status'] == 'success' && $c->subscriptionStatus == self::SUBSCRIPTION_STATUS_ACTIVE) {
            $c->status = self::STATUS_ACTIVE;
        } else {
            $c->status = self::STATUS_NOT_ACTIVE;
        }

        return $c;
    }
}
