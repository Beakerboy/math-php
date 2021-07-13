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
     * k ∈ [1,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'k' => '[1,∞)',
    ];

    /** @var int number of events */
    protected $s;

    /** @var float probability of success */
    protected $N;

    /**
     * Constructor
     *
     * @param int $s lower boundary of the distribution
     * @param int $N upper boundary of the distribution
     *
     * @throws Exception\BadDataException if b is ≤ a
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
     */
    public function pmf(int $k)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
        if ($k > $this->N) {
            throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
        }
        $s = $this->s;
        $N = $this->N;

        return (1 / $k ** $s) / array_pop(NonInteger::generalizedHarmonic($N, $s));
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
     */
    public function cdf(int $k)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
        if ($k > $this->N) {
            throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
        }

        return array_pop(NonInteger::generalizedHarmonic($k, $s)) / array_pop(NonInteger::generalizedHarmonic($N, $s));
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
