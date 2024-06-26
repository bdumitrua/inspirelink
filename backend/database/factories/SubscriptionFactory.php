<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subscriber = User::all()->random();

        $entityTypes = [User::class, Team::class];
        $entityType = $entityTypes[rand(0, 1)];
        $entity = $entityType::all()->random();

        return [
            'subscriber_id' => $subscriber->id,
            'entity_id' => $entity->id,
            'entity_type' => $entityType,
        ];
    }
}
