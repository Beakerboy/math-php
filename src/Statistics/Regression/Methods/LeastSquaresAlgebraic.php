<?php
namespace MathPHP\Statistics\Regression\Methods;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\Functions\Map\Multi;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Statistics\Average;

trait LeastSquaresAlgebraic
{
    /**
     * Regression ys
     * Since the actual xs may be translated for regression, we need to keep these
     * handy for regression statistics.
     * @var array
     */
    private $reg_ys;

    /**
     * Regression xs
     * Since the actual xs may be translated for regression, we need to keep these
     * handy for regression statistics.
     * @var array
     */
    private $reg_xs;

    /**
     * Regression Yhat
     * The Yhat for the regression xs.
     * @var array
     */
    private $reg_Yhat;

    /**
     * Projection Matrix
     * https://en.wikipedia.org/wiki/Projection_matrix
     * @var Matrix
     */
    private $reg_P;

    /**
     * Linear least squares fitting using algebra 
     *
     * Generalizing from a straight line (first degree polynomial):
     *  y = a₀ + a₁x
     *
     * The traditional way to do least squares:
     *        _ _   __
     *        x y - xy        _    _
     *   m = _________    b = y - mx
     *        _     __
     *       (x)² - x²
     *
     * @param  array $ys y values
     * @param  array $xs x values
     * @param  int $fit_constant '1' if we are fitting a constant to the regression.
     *
     * @return Matrix [[m], [b]]
     */
    public function leastSquares(array $ys, array $xs, $fit_constant = 1): Matrix
    {
        $this->reg_ys = $ys;
        $this->reg_xs = $xs;
        
        $ave_y = Average::mean($ys);
        $ave_x = Average::mean($xs);
        
        $new_xs = Single::subtract($xs, $ave_x);
        $new_ys = Single::subtract($ys, $ave_y);
        
        $num = array_sum(Multi::product($new_xs, $new_ys));
        $den = array_sum(Multi::product($new_xs, $new_xs));

        $m = $num / $den;
        $b = $ave_y - $m * $ave_x;
        $β_hat = new Matrix([$m, $b]);

        return $β_hat;
    }
