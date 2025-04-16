<?php

namespace App\Tests;

use App\Cart;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once 'example-functions.php';

class ExampleTest extends TestCase
{
    public function testTwoStringsAreTheSame()
    {
        $string1 = 'car';
        $string2 = 'scooter';

        // $this->assertSame($string1, $string2);
        // $this->assertTrue($string1 == $string2);
        $this->assertFalse($string1 == $string2);
    }

    public function testProductIsCalculatedCorrectly()
    {
        $product = product(10, 2);
        $this->assertEquals(20, $product);
        $this->assertNotEquals(10, $product);
    }

    #[DataProvider('quotientProvider')]
    public function testQuotientIsCalculatedCorrectly($a, $b, $expected)
    {
        $quotient = quotient($a, $b);

        $this->assertSame($expected, $quotient);
    }

    /**
     * If you give each array a key name, it makes it easy to identify which scenarios have failed
     */
    public static function quotientProvider(): array
    {
        return [
            '2_by_1' => [2, 1, 2],
            '9_by_3' => [9, 3, 3],
            '2_by_4' => [2, 4, 0.5],
            'division_by_zero' => [2, 0, 2]
        ];
    }

    public function testSomeAssertions()
    {
        // Demo static vs $this
        self::assertFalse(0 == 1);
        $this->assertFalse(0 == 1);

        // assertArrayHasKey
        $arr = ['foo' => 'bar'];
        $this->assertArrayHasKey('foo', $arr);
        $this->assertArrayNotHasKey('zoo', $arr);

        // assertContains
        $this->assertContains('bar', $arr);
        $this->assertNotContains('car', $arr);

        // assertStringContainsString
        $string = json_encode([
            'price' => '8.99',
            'date' => '2021-12-04'
        ]);
        $this->assertStringContainsString('"date":"2021-12-04"', $string);

        // assertInstanceof
        $cart = new Cart();
        $this->assertInstanceOf(Cart::class, $cart);

        // assertCount
        $this->assertCount(3, [1, 2, 3]);

        // assertEquals // assertSame
        $value = 21;
        $this->assertEquals('21', $value);
        $this->assertSame(21, $value);

        // assertGreatherThan (or equal)
        $value = 10;
        $this->assertGreaterThan(9, $value, 'Watch out, that is not greater than!');
        $this->assertGreaterThanOrEqual(10, $value);

        // assertIsArray
        $this->assertIsArray([1, 2, 3]);

        $this->assertEquals('localhost', URL);
    }
}