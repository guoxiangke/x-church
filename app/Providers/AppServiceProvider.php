<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Event;
use App\Models\Service;
use App\Observers\EventObserver;
use App\Observers\ServiceObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() !== 'local') {
            URL::forceScheme('https');
        }
        Event::observe(EventObserver::class);
        Service::observe(ServiceObserver::class);
    }
}
