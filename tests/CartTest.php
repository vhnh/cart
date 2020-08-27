<?php

namespace Vhnh\Cart\Tests;

use Vhnh\Cart\Tests\Fakes\Product;

class CartTest extends TestCase
{
    /** @test */
    public function it_appends_an_item()
    {
        $buyable = new Product();

        $cart = $this->cart();
        $cart->append($buyable, 1);

        $this->assertCount(1, $cart);
    }

    /** @test */
    public function it_removes_an_item()
    {
        $buyable = new Product([
            'ean' => 12345
        ]);

        $cart = $this->cart();
        $cart->append($buyable, 1);

        $cart->remove($buyable);

        $this->assertCount(0, $cart);
    }

    /** @test */
    public function it_replaces_duplicate_items()
    {
        $buyable = new Product([
            'ean' => 123456
        ]);

        $cart = $this->cart();

        $cart->append($buyable, 1);
        $cart->append($buyable, 1);

        $this->assertCount(1, $cart);
    }

    /** @test */
    public function it_has_a_total()
    {
        $cart = $this->cart();
        $cart->append(new Product(['price' => 100, 'ean' => 123456]), 1);
        $cart->append(new Product(['price' => 500, 'ean' => 345612]), 5);
        $this->assertEquals(2600, $cart->total());
    }

    /** @test */
    public function it_calculates_the_total_vat()
    {
        $cart = $this->cart();
        $cart->append(new Product(['price' => 100, 'ean' => 123456, 'vat' => 10]), 1); // 10 = (100 * 0.1)
        $cart->append(new Product(['price' => 500, 'ean' => 345612, 'vat' => 20]), 5); // 500 = (2500 * 0.2)
        

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
        $cart->append(new Product(['price' => 100, 'ean' => 123456, 'vat' => 10]), 1);
        
        app('cart')->store($cart);
        
        $cart = $this->cart();
        $this->assertCount(1, $cart);
    }

    protected function cart()
    {
        return app('cart')->resolve();
    }
}
