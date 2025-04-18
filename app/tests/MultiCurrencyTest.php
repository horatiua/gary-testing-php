<?php

namespace App\Tests;

use App\Bank;
use App\Money;
use App\Sum;
use PHPUnit\Framework\TestCase;

// 1. Quickly add a test
// 2. Run all tests to see the new one fail OR error
// 3. Make a small change
// 4. Run all tests and see them all succeed
// 5. Refactor to clean up your code

// 1. Add a test (invent the interface you wish you had)
// 2. Make the test pass (that works)
// 3. Refactor (clean code)

// - Be able to add amounts in two different currencies and convert the result
// given a set of exchange rates.
//
// - We need to be able to multiply an amount (price per share) by a number (number of shares) and receive an amount.

// ok: ￡5 + $10 = ￡10 IF rate is 2:1
// ok: Sum plus
// ok: Sum times
// Duplicate plus
// ok: ￡5 + ￡5 = ￡10
// Make augend private
// Make addend private
// Make amount private
// ok: Reduce money with currency conversion

// ok: ￡5 * 2 = ￡10
// ok: GBP side effects?
// ok: Make 'amount' private
// ok: GDP::equals
// ok: $5 * 2 = $10
// ok: Common equals
// ok: GDB / USD duplication
// ok: Common times
// ok: Compare USD with GDP
// ok: Currency?

class MultiCurrencyTest extends TestCase
{
    public function testMultiplication(): void
    {
        // Setup
        $five = Money::gbp(5);

        // Do

        // Assert
        $this->assertEquals(Money::gbp(10), $five->times(2));
        $this->assertEquals(Money::gbp(15), $five->times(3));
    }

    public function testEquality(): void
    {
        $this->assertTrue((Money::gbp(5))->equals(Money::gbp(5)));
        $this->assertFalse((Money::gbp(5))->equals(Money::gbp(6)));

        $this->assertFalse((Money::usd(5))->equals(Money::gbp(5)));
    }

    public function testCurrency(): void
    {
        $this->assertEquals('GBP', Money::gbp(1)->currency());
        $this->assertEquals('USD', Money::usd(1)->currency());
    }

    public function testSimpleAddition(): void
    {
        // Setup
        $five = new Money(5, 'GBP');
        $bank = new Bank();

        // Do
        $sum = $five->plus($five);
        $reduced = $bank->reduce($sum, 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(10), $reduced);
    }

    public function testPlusReturnsSum(): void
    {
        // Setup
        $five = Money::gbp(5);

        // Do
        $sum = $five->plus($five);

        // Assert
        $this->assertInstanceOf(Sum::class, $sum);
        $this->assertEquals($five, $sum->augend);
        $this->assertEquals($five, $sum->addend);
    }

    public function testReduceSum()
    {
        // Setup
        $sum = new Sum(Money::gbp(3), Money::gbp(4));
        $bank = new Bank();

        // Do
        $result = $bank->reduce($sum, 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(7), $result);
    }

    public function testReduceMoney()
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('GBP', 'GBP', 123);

        // Do
        $result = $bank->reduce(Money::gbp(1), 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(1), $result);
    }

    public function testReduceMoneyUsingDifferentCurrency(): void
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('USD', 'GBP', 1);
        $bank->addRate('USD', 'GBP', 2);

        // Do
        $result = $bank->reduce(Money::usd(2), 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(1), $result);
    }

    public function testMixedAddition(): void
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('USD', 'GBP', 2);
        $fiveGbp = Money::gbp(5);
        $tenUsd = Money::usd(10);

        // Do
        $resultGBP = $bank->reduce($fiveGbp->plus($tenUsd), 'GBP');
        $resultUSD = $bank->reduce($tenUsd->plus($fiveGbp), 'USD');

        // Assert
        $this->assertEquals(Money::gbp(10), $resultGBP);
        $this->assertEquals(Money::usd(20), $resultUSD);
    }

    public function testSumPlusMoney(): void
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('USD', 'GBP', 2);
        $fiveGbp = Money::gbp(5);
        $tenUsd = Money::usd(10);

        // Do
        $result = $bank->reduce($fiveGbp->plus($tenUsd)->plus($fiveGbp), 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(15), $result);
    }

    public function testSumTimes(): void
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('USD', 'GBP', 2);
        $fiveGbp = Money::gbp(5);
        $tenUsd = Money::usd(10);

        // Do
        $result = $bank->reduce($fiveGbp->plus($tenUsd)->times(2), 'GBP');

        // Assert
        $this->assertEquals(Money::gbp(20), $result);
    }

    public function testLetsGoCrazy(): void
    {
        // Setup
        $bank = new Bank();
        $bank->addRate('USD', 'GBP', 2);
        $fiveGbp = Money::gbp(5);
        $tenUsd = Money::usd(10);

        // Do
        $result = $bank->reduce(
            $fiveGbp->plus($tenUsd)->times(2)
                ->plus($fiveGbp->plus($tenUsd))
                ->plus($fiveGbp->plus($tenUsd))
                ->times(4),
            'GBP'
        );

        // Assert
        $this->assertEquals(Money::gbp(160), $result);
    }
}