<?php

namespace Vhnh\Cart\Tests;

use Vhnh\Cart\Cart;
use Vhnh\Cart\CartItem;
use Vhnh\Cart\Tests\Fakes\Product;
use Vhnh\Cart\Exceptions\DuplicateCartItemException;

class CartTest extends TestCase
{
    /** @test */
    public function it_may_has_cart_items()
    {
        $buyable = new Product;

        $cart = Cart::make();

        $cart->add($buyable, 1);

        $this->assertCount(1, $cart->items());
    }

    /** @test */
    public function it_does_not_allow_the_same_buyable_multiple_times()
    {
        $this->expectException(DuplicateCartItemException::class);

        $buyable = new Product([
            'ean' => 123456
        ]);

        $cart = Cart::make();

        $cart->add($buyable, 1);
        $cart->add($buyable, 1);
    }

    /** @test */
    public function it_has_a_total()
    {
        $items = collect([
            new CartItem(new Product(['price' => 100, 'ean' => 123456]), 1), // 100
            new CartItem(new Product(['price' => 500, 'ean' => 345612]), 5), // 2500
        ]);

        $cart = Cart::make($items);
        $this->assertEquals(2600, $cart->total());
    }

    /** @test */
    public function it_calculates_the_total_vat()
    {
        $items = collect([
            new CartItem(new Product(['price' => 100, 'ean' => 123456, 'vat' => 10]), 1), // 10 = (100 * 0.1)
            new CartItem(new Product(['price' => 500, 'ean' => 345612, 'vat' => 20]), 5), // 500 = (2500 * 0.2)
        ]);
        
        $cart = Cart::make($items);

        $this->assertEquals([
            '10%' => 10,
            '20%' => 500,
        ], $cart->vat()->toArray());

        $this->assertEquals(510, $cart->totalVat());
    }
}
