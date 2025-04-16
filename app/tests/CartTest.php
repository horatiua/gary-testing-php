<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cart;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    protected Cart $cart;

    protected function setUp(): void
    {
        // Setup
        Cart::setTax(1.2);
        $this->cart = new Cart();
    }

    protected function tearDown(): void
    {
        // Cart::setTax(1.2);
    }

    public function testTheCartTaxValueCanBeChangedStatically()
    {
        // Setup
        $this->cart->setPrice(10);

        // Do something
        Cart::setTax(1.5);
        $netPrice = $this->cart->getNetPrice();

        // Make assertions
        $this->assertEquals(15, $netPrice);
    }

    public function testNetPriceIsCalculatedCorrectly()
    {
        // Setup
        $this->cart->setPrice(10);

        // When
        // Do something
        $netPrice = $this->cart->getNetPrice();

        // Make assertions
        $this->assertEquals(12, $netPrice);
    }

    public function testErrorHappensWhenPriceIsSetAsString()
    {
        try {
            // Setup
            $this->cart->setPrice('5.99');

            $this->fail('Price can not be a float');
        } catch (\Throwable $throwable) {
            $this->assertStringContainsString(
                'must be of type float',
                $throwable->getMessage()
            );
        }
    }
}