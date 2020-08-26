<?php

namespace Vhnh\Cart\Tests;

use Vhnh\Cart\Contracts\Buyable;
use Vhnh\Cart\Tests\Fakes\Product;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(Buyable::class, Product::class);
    }

    protected function getPackageProviders($app)
    {
        return ['Vhnh\Cart\ServiceProvider'];
    }
}
