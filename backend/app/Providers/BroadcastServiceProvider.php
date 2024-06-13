<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->hasHeader('authorization')) {
            Broadcast::routes(["middleware" => "auth:api"]);
        } else {
            Broadcast::routes();
        }

        require base_path('routes/channels.php');
    }
}
