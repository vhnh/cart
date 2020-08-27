<?php

namespace Vhnh\Cart;

use Illuminate\Session\SessionManager;

class CartManager
{
    protected $session;
    
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function resolve()
    {
        if ($this->session->has('cart')) {
            return Cart::hydrate($this->session->get('cart'));
        }

        return new Cart([]);
    }

    public function store(Cart $cart)
    {
        $this->session->put('cart', $cart->toArray());
    }
}
