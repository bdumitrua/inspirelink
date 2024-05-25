<?php

namespace App\Repositories\User;

use App\Models\UserSubscription;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserSubscriptionRepository implements UserSubscriptionRepositoryInterface
{
    public function getByBothIds(int $subscriberId, int $subscribedId): ?UserSubscription
    {
        return UserSubscription::where([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ])
            ->first();
    }

    public function subscribers(int $userId): array
    {
        return UserSubscription::where('subscribed_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscriber_id'];
            })
            ->toArray();
    }

    public function subscriptions(int $userId): array
    {
        return UserSubscription::where('subscriber_id', '=', $userId)
            ->get()
            ->map(function ($subscription) {
                return $subscription['subscribed_id'];
            })
            ->toArray();
    }

    public function subscribe(int $subscriberId, int $subscribedId): void
    {
        UserSubscription::create([
            'subscriber_id' => $subscriberId,
            'subscribed_id' => $subscribedId,
        ]);
    }

    public function unsubscribe(UserSubscription $userSubscription): void
    {
        $userSubscription->delete();
    }
}