<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        $this->registerPolicies();

        Schema::defaultStringLength(191);

        Passport::routes();

        Passport::enableImplicitGrant();
    }

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //
    }
}
