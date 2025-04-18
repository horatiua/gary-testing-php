<?php

namespace App;

class Bank
{
    private array $rates = [];

    public function reduce(Expression $source, string $targetCurrency): Expression
    {
        return $source->reduce($this, $targetCurrency);
    }

    public function addRate(string $from, string $to, float $rate): void
    {
        $this->rates = array_replace(
            $this->rates,
            [
                $from . $to => $rate,
                $to . $from => 1 / $rate
            ]
        );
    }

    public function rate(string $from, string $to)
    {
        if ($from === $to) {
            return 1;
        }

        return $this->rates[$from . $to];
    }
}