<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * χ²-distribution (Chi-squared)
 * https://en.wikipedia.org/wiki/Chi-squared_distribution
 */
class ChiSquared extends Continuous
{
    /**
     * Distribution support bounds limits
     * x ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '[0,∞)',
    ];

    /**
     * Distribution parameter bounds limits
     * k ∈ [1,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'k' => '[1,∞)',
    ];

    /** @var number Degrees of Freedom Parameter */
    protected $k;

    /**
     * Probability density function
     *
     *                 1
     *           -------------- x⁽ᵏ/²⁾⁻¹ ℯ⁻⁽ˣ/²⁾
     *  χ²(k) =          / k \
     *           2ᵏ/² Γ |  -  |
     *                   \ 2 /
     *
     * @param number $x point at which to evaluate > 0
     *
     * @return number probability
     */
    public function pdf($x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $k = $this->k;
        // Numerator
        $x⁽ᵏ／²⁾⁻¹ = $x**(($k / 2) - 1);
        $ℯ⁻⁽ˣ／²⁾  = exp(-($x / 2));

        // Denominator
        $２ᵏ／²  = 2**($k / 2);
        $Γ⟮k／2⟯ = Special::Γ($k / 2);

        return ($x⁽ᵏ／²⁾⁻¹ * $ℯ⁻⁽ˣ／²⁾) / ($２ᵏ／² * $Γ⟮k／2⟯);
    }

    /**
     * Cumulative distribution function
     *
     * Cumulative t value up to a point, left tail.
     *
     *          / k   x  \
     *       γ |  - , -  |
     *          \ 2   2 /
     * CDF = -------------
     *            / k \
     *         Γ |  -  |
     *            \ 2 /
     *
     * @param number $x Chi-square critical value (CV) > 0
     * @param int    $k degrees of freedom > 0
     *
     * @return number cumulative probability
     */
    public function cdf($x)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x]);

        $k = $this->k;
        // Numerator
        $γ⟮k／2、x／2⟯ = Special::γ($k / 2, $x / 2);

        // Demoninator
        $Γ⟮k／2⟯ = Special::Γ($k / 2);

        return $γ⟮k／2、x／2⟯ / $Γ⟮k／2⟯;
    }
    
    /**
     * Mean of the distribution
     *
     * μ = k
     *
     * @return int k
     */
    public function mean()
    {
        return $this->k;
    }
}
