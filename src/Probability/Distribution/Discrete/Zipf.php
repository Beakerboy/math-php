<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;
use MathPHP\Functions\Support;
use MathPHP\Sequence\NonInteger;

/**
 * Zipf's Law
 * https://en.wikipedia.org/wiki/Zipf's_law
 */
class Zipf extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * s ∈ [0,∞)
     * N ∈ [1,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        's' => '[0,∞)',
        'N' => '[1,∞)',
    ];

    /**
     * Distribution support bounds limits
     * Rank
     * k ∈ [1,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'k' => '[1,∞)',
    ];

    /** @var number Characterizing exponenet */
    protected $s;

    /** @var int Number of elements */
    protected $N;

    /**
     * Constructor
     *
     * @param number $s exponent
     * @param int $N elements
     */
    public function __construct($s, int $N)
    {
        parent::__construct($s, $N);
    }

    /**
     * Probability mass function
     *
     *            1
     * pmf = -----------
     *         kˢ * Hₙ,ₛ
     *
     * @param int $k 
     *
     * @return number
     *
     * @throws Exception\OutOfBoundsException if k is > N
     */
    public function pmf(int $k)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
        if ($k > $this->N) {
            throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
        }
        $s = $this->s;
        $N = $this->N;
        $denominator = array_pop(NonInteger::generalizedHarmonic($N, $s));
        return 1 / ($k ** $s) / $denominator;
    }

    /**
     * Cumulative distribution function
     *
     *           Hₖ,ₛ
     * pmf = ---------
     *           Hₙ,ₛ
     *
     *
     * @param int $k 
     *
     * @return number
     *
     * @throws Exception\OutOfBoundsException if k is > N
     */
    public function cdf(int $k)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
        if ($k > $this->N) {
            throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
        }
        $numerator = array_pop(NonInteger::generalizedHarmonic($k, $s));
        $denominator = array_pop(NonInteger::generalizedHarmonic($N, $s))
        return $numerator / $denominator;
    }

    /**
     * Mean of the distribution
     *
     *       Hₖ,ₛ₋₁
     * μ = ---------
     *        Hₙ,ₛ
     *
     * @return number
     */
    public function mean()
    {
        return array_pop(NonInteger::generalizedHarmonic($N, $s - 1)) / array_pop(NonInteger::generalizedHarmonic($N, $s));
    }

    /**
     * Mode of the distribution
     *
     * μ = 1
     *
     * @return number
     */
    public function mode()
    {
        return 1;
    }
}
