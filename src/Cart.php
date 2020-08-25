<?php

namespace Vhnh\Cart;

use Vhnh\Cart\Contracts\Buyable;
use Illuminate\Support\Collection;
use Vhnh\Cart\Exceptions\DuplicateCartItemException;

class Cart
{
    protected $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }
    
    public static function make(?Collection $items = null)
    {
        $items = $items ?: new Collection([]);

        return new static($items);
    }

    public function add(Buyable $buyable, $quantity)
    {
        if ($this->exists($buyable)) {
            throw new DuplicateCartItemException(
                sprintf('The item "%s" already exits in your cart.', $buyable->ean())
            );
        }

        $this->items->add(
            new CartItem($buyable, $quantity)
        );
    }

    public function items()
    {
        return $this->items;
    }

    public function total()
    {
        return $this->items->sum->price();
    }

    public function vat()
    {
        return $this->items->groupBy->vatFactor()->map->sum(function ($item) {
            return $item->vat();
        });
    }

    public function totalVat()
    {
        return $this->vat()->sum();
    }

    public function exists(Buyable $buyable)
    {
        return $this->items->contains(function ($item) use ($buyable) {
            return $item->ean() === $buyable->ean();
        });
    }
}
