<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // $rachow - horizon alerts
        // Horizon::routeSmsNotificationsTo('012343444444');
        // Horizon::routeMailNotificationsTo('devops@coinhoppa.com');
        // Horizon::routeSlackNotificationsTo('https://coinhoppa.slack.com', '#devops');
    }

    /**
     * Authorize access to horizon
    */
    protected function authorization()
    {
        $this->gate();
         
        Horizon::auth(function ($request) {
            return Gate::check('viewHorizon', []) || 
                app()->environment(['local', 'development', 'staging']);
        });
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user) {
            return in_array($user->email, [
                'devops@coinhoppa.com',
            ]);
        });
    }
}
