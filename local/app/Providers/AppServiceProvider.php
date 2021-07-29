<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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

        view()->composer('*', function ($view) {
            $view->with([
                'url' => env('APP_ENV') === 'local' ? 'http://localhost' : 'https://v3.aiyara.co.th',
                'canAccess' => !session()->has('access_from_admin') ? 1 : 0
            ]);
        });
    }
}
