<?php

namespace Vhnh\Cart\Contracts;

interface Buyable
{
    public function ean();

    public function price();

    public function vat();
}
