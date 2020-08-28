<?php

namespace Vhnh\Cart\Contracts;

interface Buyable
{
    public function ean();

    public function price();

    public function vat();

    public function fill(array $attributes);

    public function toArray();
}
