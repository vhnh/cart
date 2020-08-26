<?php

namespace Vhnh\Cart;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('cart', Cart::class);
    }

    public function boot()
    {
        //
    }
}
