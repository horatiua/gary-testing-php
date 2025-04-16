<?php

namespace App;

class Cart
{
    private float $price;
    public static float $tax = 1.2;

    public function getNetPrice(): float
    {
        return $this->price * self::$tax;
    }

    public static function setTax(float $tax): void
    {
        self::$tax = $tax;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}