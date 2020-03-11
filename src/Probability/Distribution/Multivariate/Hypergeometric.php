<?php

namespace MathPHP\Probability\Distribution\Multivariate;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;
use MathPHP\Functions\Support;

/**
 * Multivariate Hypergeometric distribution (multivariate)
 *
 * https://en.wikipedia.org/wiki/Multinomial_distribution
 */
class Hypergeometric
{
    /**
     * Distribution parameter bounds limits
     * Kᵢ ∈ [1,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'K' => '[1,∞)',
    ];

    /**
     * Distribution parameter bounds limits
     * kᵢ ∈ [0,Kᵢ]
     * @var array
     */
    $supportLimits = [];

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
        foreach ($quantities as $K) {
            Support::checkLimits(self::PARAMETER_LIMITS, ['K' => $K]);
            $this->supportLimits['k'][] = "[0,$K]";
        }
        $this->quantities = $quantities;
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
        foreach ($picks as $i => $k) {
            if (!is_int($k)) {
                throw new Exception\BadDataException("Picks must be whole numbers.");
            }
            Support::checkLimits($this->supportLimits['k'][$i], ['k' => $k]);
        }

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
