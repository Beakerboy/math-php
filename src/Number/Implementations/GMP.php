<?php

namespace MathPHP\Number\Implementations;

use MathPHP\Number\ObjectArithmetic;

class GMP implements ObjectArithmetic
{
    public function __construct($number)
    {
        $this->value = gmp_init($number);
    }

    public function __toString()
    {
        return $this->value;
    }

    public function fact()
    {
    }

    public function add($number)
    {
        return new ArbitraryInteger(gmp_add($this->value, $number));
    }

    public function subtract($number)
    {
    }

    public function multiply($number)
    {
        return new ArbitraryIntger(gmp_mul($this->value, $number));
    }

    public function intdiv($number)
    {
    }

    public function mod($number)
    {
    }

    public function pow($number)
    {
    }

    public function equals($number)
    {
    }

    public function greaterThan($number)
    {
    }

    public function lessThan($number)
    {
    }
}
