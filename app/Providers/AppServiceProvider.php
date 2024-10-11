<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Service;
use App\Observers\EventObserver;
use App\Observers\EventEnrollObserver;
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
        EventEnroll::observe(EventEnrollObserver::class);
        Service::observe(ServiceObserver::class);
        \Illuminate\Support\Facades\Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('laravelpassport', \SocialiteProviders\LaravelPassport\Provider::class);
        });
    }
}
