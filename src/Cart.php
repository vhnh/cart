<?php

namespace Vhnh\Cart;

use Vhnh\Cart\Contracts\Buyable;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Vhnh\Cart\Exceptions\DuplicateCartItemException;

class Cart
{
    protected $items;
    
    protected $session;
    
    protected $name = 'default';

    public function __construct(SessionManager $session)
    {
        $this->session = $session;

        $this->resolveItems();
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

        $this->save();
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

    protected function save()
    {
        $this->session->put('cart.'.$this->name, $this->items->toArray());
    }

    protected function resolveItems()
    {
        if ($this->session->has('cart.'.$this->name)) {
            $this->items = new Collection(
                array_map(function ($item) {
                    return CartItem::hydrate($item);
                }, $this->session->get('cart.'.$this->name))
            );
        } else {
            $this->items = new Collection([]);
        }
    }
}
