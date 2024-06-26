<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
            TeamLinkSeeder::class,
            TeamVacancySeeder::class,
            TeamApplicationSeeder::class,
            SubscriptionSeeder::class,
            PostSeeder::class,
            PostCommentSeeder::class,
            TagSeeder::class,
        ]);
    }
}
