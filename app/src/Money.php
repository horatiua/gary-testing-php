<?php

namespace App;

class Money implements Expression
{
    public ?float $amount = null;
    protected string $currency;

    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(Money $money): bool
    {
        return $this->currency === $money->currency
            && $this->amount === $money->amount;
    }

    public function reduce(Bank $bank, string $to): Money
    {
        $rate = $bank->rate($this->currency, $to);

        return new Money($this->amount / $rate, $to);
    }

    public function plus(Expression $added): Expression
    {
        return new Sum($this, $added);
    }

    public function times(int $multiplier): Expression
    {
        return new Money($this->amount * $multiplier, $this->currency);
    }

    public static function gbp(float $amount): Money
    {
        return new Money($amount, 'GBP');
    }

    public static function usd(float $amount): Money
    {
        return new Money($amount, 'USD');
    }
}