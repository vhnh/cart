<?php

namespace Vhnh\Cart\Tests;

use Vhnh\Cart\CartItem;
use Vhnh\Cart\Tests\Fakes\Product;

class CartItemTest extends TestCase
{
    /** @test */
    public function it_has_a_price()
    {
        $buyable = new Product([
            'price' => 500,
        ]);

        $item = new CartItem($buyable, 2);

        $this->assertEquals(1000, $item->price());
    }

    /** @test */
    public function it_calculates_the_vat()
    {
        $buyable = new Product([
            'price' => 500,
            'vat' => 10,
        ]);

        $item = new CartItem($buyable, 2);

        $this->assertEquals(100, $item->vat());
    }
}
