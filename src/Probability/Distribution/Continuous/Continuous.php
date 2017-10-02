<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;
use MathPHP\Exception;

abstract class Continuous extends \MathPHP\Probability\Distribution\Distribution
{
    const LIMITS = [];

    protected $params;
    
    public function __construct(...$params)
    {
        $new_params = static::PARAMETER_LIMITS;
        $i = 0;
        foreach ($new_params as $key => $value) {
            $this->$key = $params[$i];
            $new_params[$key] = $params[$i];
            $i++;
        }
        Support::checkLimits(static::PARAMETER_LIMITS, $new_params);
    }
    
    /**
     * The Inverse CDF of the distribution
     *
     * For example, if the calling class CDF definition is CDF($x, $d1, $d2)
     * than the inverse is called as inverse($target, $d1, $d2)
     *
     * @param number $target   The area for which we are trying to find the $x
     *
     * @todo check the parameter ranges.
     * @return $number
     */
    public function inverse($target)
    {
        $initial = $this->mean();
        if (is_nan($initial)) {
            $initial = $this->median();
        }

        $tolerance = .0000000001;
        $dif       = $tolerance + 1;
        $guess     = $initial;

        while ($dif > $tolerance) {
            $y     = $this->cdf($guess);
            
            // Since the CDF is the integral of the PDF, the PDF is the derivative of the CDF
            $slope = $this->pdf($guess);
            $del_y = $target - $y;
            $guess = $del_y / $slope + $guess;
            $dif   = abs($del_y);
        }
        return $guess;
    }
  
    /**
     * CDF between - probability of being between two points, x₁ and x₂
     * The area under a continuous distribution, that lies between two specified points.
     *
     * P(between) = CDF($x₂) - CDF($x₁)
     *
     * @param number $x₁ Lower bound
     * @param number $x₂ Upper bound
     *
     * @return number
     */
    public function between($x₁, $x₂)
    {
        $upper_area = $this->cdf($x₂);
        $lower_area = $this->cdf($x₁);
        return $upper_area - $lower_area;
    }
  
    /**
     * CDF outside - Probability of being below x₁ and above x₂.
     * The area under a continuous distribution, that lies above and below two points.
     *
     * P(outside) = 1 - P(between) = 1 - (CDF($x₂) - CDF($x₁))
     *
     * @param number $x₁ Lower bound
     * @param number $x₂ Upper bound
     *
     * @return number
     */
    public function outside($x₁, $x₂)
    {
        return 1 - $this->between($x₁, $x₂);
    }

    /**
     * CDF above - Probability of being above x to ∞
     * Area under a continuous distribution, that lies above a specified point.
     *
     * P(above) = 1 - CDF(x)
     *
     * @param number $x
     *
     * @return number
     */
    public function above($x)
    {
        return 1 - $this->cdf($x);
    }
    
    /**
     * Produce a random number with a particular distribution
     */
    public function rand()
    {
        return $this->inverse(random_int(0, \PHP_INT_MAX) / \PHP_INT_MAX);
    }
}
