<?php

namespace MathPHP\Probability\Distribution\Multivariate;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;

/**
 * Multivariate Hypergeometric distribution (multivariate)
 *
 * https://en.wikipedia.org/wiki/Multinomial_distribution
 */
class Hypergeometric
{
    /** @var array */
    protected $quantities;

    /**
     * Multinomial constructor
     *
     * @param   array $quantities
     *
     * @throws Exception\BadDataException if the probabilities do not add up to 1
     */
    public function __construct(array $quantities)
    {
        foreach ($quantities as $i => $value) {
            if (!is_int($value) || $value < 1) {
                throw new Exception\BadDataException('Values must be positive integers.');
            }
            $this->quantities[] = $value;
        }
    }

    /**
     * Probability mass function
     *
     * @param  array $picks
     *
     * @return float
     *
     * @throws Exception\BadDataException if the number of frequencies does not match the number of probabilities
     */
    public function pmf(array $picks): float
    {
        // Must have a pick for each quantity
        if (count($picks) !== count($this->quantities)) {
            throw new Exception\BadDataException('Number of quantities does not match number of picks.');
        }
        foreach ($picks as $pick) {
            if (!is_int($pick) || $pick < 0 || $pick > $this->quantities[$i]) {
                throw new Exception\BadDataException("Picks must be whole numbers less than the corresponding quantity.");
            }
        }
        $picks = array_values($picks);

        $n       = array_sum($picks);
        $total   = array_sum($this->quantities);

        $product = array_product(array_map(
            function (int $quantity, int $pick) {
                return Combinatorics::combinations($quantity, $pick);
            },
            $this->quantities,
            $picks
        ));

        return $product / Combinatorics::combinations($total, $n);
    }
}
