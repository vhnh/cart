<?php

namespace Vhnh\Cart\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return ['Vhnh\Cart\ServiceProvider'];
    }
}
