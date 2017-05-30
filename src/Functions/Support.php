<?php
namespace MathPHP\Functions;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;

class Support
{
    /**
     * Checks that the values of the parameters passed
     * to a function fall within the defined bounds.
     * The parameter limits are defined using ISO 31-11 notation.
     * https://en.wikipedia.org/wiki/ISO_31-11
     *
     *  (a,b) = a <  x <  b
     *  [a,b) = a <= x <  b
     *  (a,b] = a <  x <= b
     *  [a,b] = a <= x <= b
     *
     * @param array $limits Boundary limit definitions for each parameter
     *                      ['var1' => limit, 'var2' => limit, ...]
     * @param array $params Parameters and their value to check against the defined limits
     *                      ['var1' => value, 'var2' => value, ...]
     *
     * @return bool True if all parameters are within defined limits
     *
     * @throws BadParameterException if a parameter without bounds limits is defined
     * @throws OutOfBoundsException if any parameter is outside the defined limits
     * @throws BadDataException if an unknown bounds character is used
     */
    public static function checkLimits(array $limits, array $params)
    {
        // All parameters should have limit bounds defined
        $undefined_limits = array_diff_key($params, $limits);
        if (!empty($undefined_limits)) {
            throw new Exception\BadParameterException('Parameter without bounds limit defined: ' . print_r($undefined_limits, true));
        }

        foreach ($params as $variable => $value) {
            // Remove the first character: ( or [
            $lower_endpoint = substr($limits[$variable], 0, 1);
            
            // Remove the last character: ) or ]
            $upper_endpoint = substr($limits[$variable], -1, 1);
            
            // Set the lower and upper limits: #,#
            list($lower_limit, $upper_limit) = explode(',', substr($limits[$variable], 1, -1));
            
            // If the lower limit is -∞, we are always in bounds.
            if ($lower_limit != "-∞") {
                switch ($lower_endpoint) {
                    case '(':
                        if ($value <= $lower_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be > {$lower_limit}");
                        }
                        break;
                    case '[':
                        if ($value < $lower_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be >= {$lower_limit}");
                        }
                        break;
                    default:
                        throw new Exception\BadDataException("Unknown lower endpoint character: {$lower_limit}");
                }
            }
            
            // If the upper limit is ∞, we are always in bounds.
            if ($upper_limit != "∞") {
                switch ($upper_endpoint) {
                    case ')':
                        if ($value >= $upper_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be < {$upper_limit}");
                        }
                        break;
                    case ']':
                        if ($value > $upper_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be <= {$upper_limit}");
                        }
                        break;
                    default:
                        throw new Exception\BadDataException("Unknown upper endpoint character: {$upper_endpoint}");
                }
            }
        }

        return true;
    }

    /*
     * Determining the constants for the Lanczos gamma approximation
     *
     * http://my.fit.edu/~gabdo/gamma.txt
     */
    public static function lanczosConstants(int $n, $g): array
    {
        // Diagonal Matrix
        $Dc_array = [];
        for ($i=0; $i<$n; $i++) {
            if ($i == 0) {
                $Dc_array[] = 2;
            } else {
                $Dc_array[] = 2 * Combinatorics::doubleFactorial(2 * $i - 1);
            }
        }
        $Dc = MatrixFactory::create($Dc_array);
        
        // Diagonal Matrix
        $Dr_array = [];
        for ($i=0; $i<$n; $i++) {
            if ($i == 0) {
                $Dr_array[] = 1;
            } else {
                $numerator = -1 * Combinatorics::factorial(2 * $i);
                $denominator = 2 * Combinatorics::factorial($i - 1) * Combinatorics::factorial($i);
                $Dr_array[] = $numerator / $denominator;
            }
        }
        $Dr = MatrixFactory::create($Dr_array);

        // Upper Triangle
        $B_array = [];
        for ($i=0; $i<$n; $i++) {
            for ($j=0; $j<$n; $j++) {
                if ($i == 0) {
                    $B_array[$i][$j] = 1;
                } elseif ($i > $j) {
                    $B_array[$i][$j] = 0;
                } else {
                    $B_array[$i][$j] = -1 ** ($j - $1) * Combinatorics::combinations($i + $j - 1, $j - $i);
                }
            }
        }
        $B = MatrixFactory::create($B_array);

        // Lower Triangle
        $C_array = [];
        for ($i=0; $i<$n; $i++) {
            for ($j=0; $j<$n; $j++) {
                if ($i == 0 && $j == 0) {
                    $C_array[$i][$j] = .5;
                } elseif ($i < $j) {
                    $C_array[$i][$j] = 0;
                } else {
                    $numerator = -1 ** ($i + $j + 2) * 4 ** $i * $j * Combinatorics::factorial($i + $j - 1);
                    $denominator = Combinatorics::factorial($i - $j) * Combinatorics::factorial(2 * $i);
                    $C_array[$i][$j] = $numerator / $denominator;
                }
            }
        }
        $C = MatrixFactory::create($C_array);

        $M = $Dr->multiply($B)->multiply($C->multiply($Dc));

        // Column vector
        $f_array = [];
        for ($i=0; $i<$n; $i++) {
            $f_array[] = \M_SQRT2 * (\M_E / (2 * ($i + $g) + 1)) ** ($i + 0.5);
        }
        $f = MatrixFactory::create([$f_array])->transpose();

        $a = $M->multiply($f);
        return ($a->scalarMultiply(exp($g) / \M_SQRT2 / \M_SQRTPI))->getColumn(0);
    }
}
