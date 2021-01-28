<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('logged-in', function ($user) {
            $name = false;
            if (env('LDAP', false)) {
                $name = $user->getName();
            } else {
                $name =  $user->name;
            }
            return $name;
        });
    }
}
