<?php

namespace MathPHP\Functions;

use MathPHP\Number\ArbitraryInteger;

/**
 * Functions that operate with ArbitraryInteger objects
 */
class ArbitraryIntegerFunctions
{
    private static function prepareParameter($number): ArbitraryInteger
    {
        if (!is_object($number)) {
            return new ArbitraryInteger($number);
        }
        // else make sure it’s a ArbitraryInteger
        return $number;
    }

    /**
     * Ackermann Function
     * A well known highly recursive function which produces very large numbers
     *
     * https://en.wikipedia.org/wiki/Ackermann_function
     *
     * @param $m
     * @param $n
     * @return ArbitraryInteger
     */
    public static function ackermann($m, $n): ArbitraryInteger
    {
        $m = self::prepareParameter($m);
        $n = self::prepareParameter($n);
        if ($m->equals(0)) {
            return $n->add(1);
        } elseif ($m->equals(1)) {
            return $n->add(2);
        } elseif ($m->equals(2)) {
            return $n->leftShift(1)->add(3);
        } elseif ($m->equals(3)) {
            $one = new ArbitraryInteger(1);
            // 2^(n+3) - 3
            return $one->leftShift($n->add(3))->subtract(3);
        } elseif ($n->equals(0)) {
            return self::ackermann($m->subtract(1), 1);
        } else {
            return self::ackermann($m->subtract(1), self::ackermann($m, $n->subtract(1)));
        }
    }
}
