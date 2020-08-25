<?php

namespace Vhnh\Cart\Tests\Fakes;

use Vhnh\Cart\Contracts\Buyable;

class Product implements Buyable
{
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function ean()
    {
        return $this->attributes['ean'];
    }

    public function vat()
    {
        return $this->attributes['vat'];
    }

    public function price()
    {
        return $this->attributes['price'];
    }
}
