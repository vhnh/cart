<?php

namespace Vhnh\Cart;

use Vhnh\Cart\Contracts\Buyable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\App;

class CartItem implements Arrayable
{
    protected $buyable;
    
    protected $quantity;

    public function __construct(Buyable $buyable, $quantity)
    {
        $this->buyable = $buyable;
        $this->quantity = $quantity;
    }

    public static function hydrate(array $attributes)
    {
        extract($attributes);

        $buyable = App::make(Buyable::class)->fill($buyable);

        return new static($buyable, $quantity);
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

    public function toArray()
    {
        return [
            'buyable' => $this->buyable->toArray(),
            'quantity' => $this->quantity,
        ];
    }
}
