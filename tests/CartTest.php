<?php

namespace Vhnh\Cart\Tests;

use Vhnh\Cart\Tests\Fakes\Product;
use Vhnh\Cart\Exceptions\DuplicateCartItemException;

class CartTest extends TestCase
{
    /** @test */
    public function it_may_has_cart_items()
    {
        $buyable = new Product;

        $cart = $this->cart();

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

        $cart = $this->cart();

        $cart->add($buyable, 1);
        $cart->add($buyable, 1);
    }

    /** @test */
    public function it_has_a_total()
    {
        $cart = $this->cart();
        $cart->add(new Product(['price' => 100, 'ean' => 123456]), 1);
        $cart->add(new Product(['price' => 500, 'ean' => 345612]), 5);
        $this->assertEquals(2600, $cart->total());
    }

    /** @test */
    public function it_calculates_the_total_vat()
    {
        $cart = $this->cart();
        $cart->add(new Product(['price' => 100, 'ean' => 123456, 'vat' => 10]), 1); // 10 = (100 * 0.1)
        $cart->add(new Product(['price' => 500, 'ean' => 345612, 'vat' => 20]), 5); // 500 = (2500 * 0.2)
        

        $this->assertEquals([
            '10%' => 10,
            '20%' => 500,
        ], $cart->vat()->toArray());

        $this->assertEquals(510, $cart->totalVat());
    }

    /** @test */
    public function it_is_stored_in_the_users_session()
    {
        $cart = $this->cart();
        $cart->add(new Product(['price' => 100, 'ean' => 123456, 'vat' => 10]), 1);
        
        $this->assertCount(1, app('session')->get('cart.default'));
        
        $cart = $this->cart();
        $this->assertCount(1, $cart->items());
    }

    protected function cart()
    {
        return app('cart');
    }
}
