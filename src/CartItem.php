<?php

namespace Vhnh\Cart;

use Vhnh\Cart\Contracts\Buyable;

use function PHPSTORM_META\argumentsSet;

class CartItem
{
    protected $buyable;
    
    protected $qunatity;

    public function __construct(Buyable $buyable, $quantity)
    {
        $this->buyable = $buyable;
        $this->quantity = $quantity;
    }

    public function buyable()
    {
        return $this->buyable;
    }

    public function price()
    {
        return $this->buyable->price() * $this->quantity;
    }

    public function vat()
    {
        return $this->price() * $this->buyable->vat() / 100;
    }

    public function vatFactor()
    {
        return $this->buyable->vat() . "%";
    }

    public function __call($method, $arguments)
    {
        return $this->buyable->$method(...$arguments);
    }
}
