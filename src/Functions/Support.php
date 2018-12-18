<?php
namespace MathPHP\Functions;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\ObjectSquareMatrix;
use MathPHP\Number\BigNumber;

class Support
{
    const ε = 0.000000000001;

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
     * @throws Exception\BadParameterException if a parameter without bounds limits is defined
     * @throws Exception\OutOfBoundsException if any parameter is outside the defined limits
     * @throws Exception\BadDataException if an unknown bounds character is used
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
                            throw new Exception\OutOfBoundsException("{$variable} must be > {$lower_limit} (lower bound), given {$value}");
                        }
                        break;
                    case '[':
                        if ($value < $lower_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be >= {$lower_limit} (lower bound), given {$value}");
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
                            throw new Exception\OutOfBoundsException("{$variable} must be < {$upper_limit} (upper bound), given {$value}");
                        }
                        break;
                    case ']':
                        if ($value > $upper_limit) {
                            throw new Exception\OutOfBoundsException("{$variable} must be <= {$upper_limit} (upper bound), given {$value}");
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
     * Calculate n+1 parameters for the Lanczos gamma approximation
     *
     * http://my.fit.edu/~gabdo/gamma.txt
     *
     * @param int $n
     * @param     $g
     *
     * @return array n+1 elements
     */
    public static function lanczosConstants(int $n, $g): array
    {
        // Diagonal Matrix
        $Dc_array = [];
        for ($i=0; $i<=$n; $i++) {
            for ($j=0; $j<=$n; $j++) {
                if ($i==$j) {
                    if ($i == 0) {
                        $Dc_array[$i][$j] = new BigNumber('2');
                    } else {
                        $Dc_array[$i][$j] = new BigNumber(strval(2 * Combinatorics::doubleFactorial(2 * $i - 1)));
                    }
                } else {
                    $Dc_array[$i][$j] = new BigNumber('0');
                }
            }
        }
        $Dc = MatrixFactory::create($Dc_array);
       // echo "\nDC=" . $Dc . "\n";
        
        // Diagonal Matrix
        $Dr_array = [];
        for ($i=0; $i<=$n; $i++) {
            for ($j=0; $j<=$n; $j++) {
                if ($i==$j) {
                    if ($i == 0) {
                        $Dr_array[$i][$j] = new BigNumber('1');
                    } else {
                        $numerator = -1 * Combinatorics::factorial(2 * $i);
                        $denominator = 2 * Combinatorics::factorial($i - 1) * Combinatorics::factorial($i);
                        $Dr_array[$i][$j] = new BigNumber(strval($numerator / $denominator));
                    }
                } else {
                    $Dr_array[$i][$j] = new BigNumber('0');
                }
            }
        }
        $Dr = new ObjectSquareMatrix($Dr_array);
       // echo "\nDr=" . $Dr . "\n";
        // Upper Triangle
        $B_array = [];
        for ($i=0; $i<=$n; $i++) {
            for ($j=0; $j<=$n; $j++) {
                if ($i == 0) {
                    $B_array[$i][$j] = new BigNumber('1');
                } elseif ($i > $j) {
                    $B_array[$i][$j] = new BigNumber('0');
                } else {
                    $B_array[$i][$j] = new BigNumber(strval((-1) ** ($j - $i) * Combinatorics::combinations($i + $j - 1, $j - $i)));
                }
            }
        }
        $B = new ObjectSquareMatrix($B_array);
       // echo "\nB=" . $B . "\n";
        // Lower Triangle
        $C_array = [];
        for ($i=0; $i<=$n; $i++) {
            for ($j=0; $j<=$n; $j++) {
                if ($i == 0 && $j == 0) {
                    $C_array[$i][$j] = new BigNumber('.5');
                } elseif ($i < $j) {
                    $C_array[$i][$j] = new BigNumber('0');
                } else {
                    $numerator = (-1) ** ($i + $j + 2) * 4 ** $j * $i * Combinatorics::factorial($i + $j - 1);
                    $denominator = Combinatorics::factorial($i - $j) * Combinatorics::factorial(2 * $j);
                    $C_array[$i][$j] = new BigNumber(strval($numerator / $denominator));
                }
            }
        }
        $C = new ObjectSquareMatrix($C_array);
       // echo "\nC=" . $C . "\n";
        $M1 = $Dr->multiply($B);
        $M2 = $C->multiply($Dc);
        $M = $M1->multiply($M2);
       // echo "\nM=" . $M . "\n";
        // Column vector
        $f_array = [];
        for ($i=0; $i<=$n; $i++) {
            $f_array[] = new BigNumber(strval(\M_SQRT2 * (\M_E / (2 * ($i + $g) + 1)) ** ($i + 0.5)));
        }
        $ft = MatrixFactory::create([$f_array]);
        $f = $ft->transpose();
       // echo "\nf=" . $f . "\n";
        $a = $M->multiply($f);
        // echo "\na=" . $a . "\n";
        $a_array = $a->getColumn(0);
        foreach ($a_array as $key => $value) {
            $a_array[$key] = floatval($value->__ToString());
        }
        $a_mat = MatrixFactory::create([$a_array])->transpose();
        $results = $a_mat->scalarMultiply(exp($g) / \M_SQRT2 / \M_SQRTPI);
        // echo "\nresults=" . $results . "\n";
        return ($results->getColumn(0));
    }

    /**
     * Is the number equivalent to zero?
     * Due to floating-point arithmetic, zero might be represented as an infinitesimal quantity.
     *
     * @param  float $x
     *
     * @return boolean true if equivalent to zero; false otherwise
     */
    public static function isZero(float $x): bool
    {
        return ($x == 0 || abs($x) <= self::ε);
    }

    /**
     * Is the number equivalent to a non-zero value?
     * Due to floating-point arithmetic, zero might be represented as an infinitesimal quantity.
     *
     * @param  float $x
     *
     * @return boolean true if equivalent to a non-zero value; false otherwise
     */
    public static function isNotZero(float $x): bool
    {
        return ($x != 0 && abs($x) > self::ε);
    }
}
