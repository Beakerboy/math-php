<?php

namespace MathPHP\Sequence;

/**
 * Non-integer sequences
 *  - Harmonic
 *  - Hyperharmonic
 *
 * All sequences return an array of numbers in the sequence.
 * The array index starting point depends on the sequence type.
 */
class NonInteger
{
    /**
     * Harmonic Numbers
     *
     *      n  1
     * Hᵢ = ∑  -
     *     ⁱ⁼¹ i
     *
     * https://en.wikipedia.org/wiki/Harmonic_number
     *
     * @param int $n the length of the sequence to calculate
     *
     * @return array
     */
    public static function harmonic(int $n): array
    {
        return self::generalizedHarmonic($n, 1);
    }

    /**
     * Generalized Harmonic Numbers
     *
     *       ₙ  1
     * Hₙₘ = ∑  --
     *      ⁱ⁼¹ iᵐ
     *
     * https://en.wikipedia.org/wiki/Harmonic_number#Generalized_harmonic_numbers
     *
     * @param int   $n the length of the sequence to calculate
     * @param float $m the exponent
     *
     * @return array
     */
    public static function generalizedHarmonic(int $n, float $m): array
    {
        if ($n <= 0) {
            return [];
        }

        $sequence = [];
        $∑        = 0;

        for ($i = 1; $i <= $n; $i++) {
            $∑ += 1 / $i ** $m;
            $sequence[$i] = $∑;
        }

        return $sequence;
    }

    /**
     * Hyperharmonic Numbers
     *
     *         ₙ
     * Hₙ⁽ʳ⁾ = ∑  Hₖ⁽ʳ⁻¹⁾
     *        ᵏ⁼¹
     *
     * https://en.wikipedia.org/wiki/Hyperharmonic_number
     *
     * @param int $n the length of the sequence to calculate
     * @param int $r the depth of recursion
     *
     * @return array
     */
    public static function hyperharmonic(int $n, int $r): array
    {
        if ($n <= 0) {
            return [];
        }
        $sequence = [];
        $∑        = 0;
        
        for ($k = 1; $k <= $n; $k++) {
            $array = self::hyperharmonic($k, $r -1);
            $value = array_pop($array);
            $∑ += $value;
            $sequence[$k] = $∑;
        }

        return $sequence;
    }
}
