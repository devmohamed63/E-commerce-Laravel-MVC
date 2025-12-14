<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', function ($view) {
            if (auth()->check()) {
                $cart = session()->get('cart', []);
                $view->with('cartCount', count($cart));
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}
