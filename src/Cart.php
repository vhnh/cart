<?php

namespace Vhnh\Cart;

use Vhnh\Cart\Contracts\Buyable;
use Illuminate\Support\Collection;

class Cart extends Collection
{
    protected $items;

    public function append(Buyable $buyable, $quantity)
    {
        $this->remove($buyable)->add(new CartItem($buyable, $quantity));

        return $this;
    }

    public function remove(Buyable $buyable)
    {
        $this->items = array_filter($this->items, function ($item) use ($buyable) {
            return $item->ean() !== $buyable->ean();
        });

        return $this;
    }

    public function total()
    {
        return $this->sum->price();
    }

    public function vat()
    {
        return $this->groupBy->vatFactor()->map->sum(function ($item) {
            return $item->vat();
        });
    }

    public function totalVat()
    {
        return $this->vat()->sum();
    }

    public static function hydrate($items)
    {
        return new static(array_map(function ($item) {
            return CartItem::hydrate($item);
        }, $items));
    }
}
