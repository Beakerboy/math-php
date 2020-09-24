<?php

namespace MathPHP\Statistics\Multivariate;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\LinearAlgebra\Eigenvalue;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Probability\Distribution\Continuous\F;
use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Statistics\Descriptive;

/**
 * Partial Least Squares Regression
 *
 * Using the NIPALS PLS1 or PLS2 algorithms
 *
 * https://en.wikipedia.org/wiki/Partial_least_squares_regression
 */
class PLS
{
    /** @var Matrix Dataset */
    private $X;
 
    /** @var Matrix Dataset */
    private $Y;

    /** @var Vector Means */
    private $Xcenter;

    /** @var Vector Means */
    private $Ycenter;
 
    /** @var Vector Scale */
    private $Xscale;

    /** @var Vector Scale */
    private $Yscale;

    /** @var Matrix $W X Weights*/
    private $W = null;

    /** @var Matrix $T X Scores*/
    private $T = null;
    
    /** @var Matrix $C Y Loadings*/
    private $C = null;

    /** @var Matrix $U Y Scores*/
    private $U = null;

    /** @var Matrix $P X Loadings*/
    private $P = null;

    /** @var Matrix $Q */
    private $Q = null;

    /** @var Matrix $B */
    private $B = null;
    /**
     * Constructor
     *
     * @param Matrix $X each row is a sample, each column is a variable
     * @param Matrix $Y each row is a sample, each column is a variable
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(Matrix $X, Matrix $Y)
    {
        // Check that X and Y have the same amount of data.
        if ($X->getM() !== $Y->getM()) {
            throw new Exception\BadDataException('X and Y must have the same number of rows.');
        }

        // Standardize X and Y
        $this->Xcenter = $X->columnMeans();
        $this->Ycenter = $Y->columnMeans();
        $this->Xscale = self::columnStdevs($X);
        $this->Yscale = self::columnStdevs($Y);
        $E = $this->standardizeData();
        $F = $this->standardizeData($Y, $this->Ycenter, $this->Yscale);

        $new_u = MatrixFactory::random();
        $tol = 1E-6;
        for ($i = 0; $i < $Y->getN(); $i++) {
            do {
                $u = $new_u;

                // $w is a unit vector
                $w = $E->transpose()->multiply($u);
                $abs_w = $w->frobeniusNorm();
                $w = $w->scalarDivide($abs_w);

                $t = $E->multiply($w);
                $c = self::RTO($F, $t);
                $new_u = $F->multiply($c);
                $diff = $new_u->subtract($u)->frobeniusNorm();
            } while ($diff > $tol);
            $p = self::RTO($E, $t);
            $q = self::RTO($F, $u);
            $d = self::RTO($u, $t)->getValue(0, 0);
            $E = $E->subtract($t->multiply($p->transpose()));
            $F = $F->subtract($t->multiply($c->transpose())->scalarMultiply($d));

            // Add each of these columns to the overall matrices
            $this->W = $this->W ?? $w : $W->augmentRight($w);
            $this->T = $this->T ?? $t : $T->augmentRight($t);
            $this->U = $this->U ?? $u : $U->augmentRight($u);
            $this->C = $this->C ?? $c : $C->augmentRight($c);
            $this->P = $this->P ?? $p : $P->augmentRight($p);
        }
       
        // Calculate R or Wstar
        $R = $W->multiply($P->transpose()->multiply($W)->inverse());
        $this->B = $R->multiply($C->transpose());
    }

    public function getB()
    {
        return $this->B;
    }

    /**
     * Standardize the data
     * Use the object $Xcenter and $Xscale Vectors to transform the provided data
     *
     * @param Matrix $new_data - An optional Matrix of new data which is standardized against the original data
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    public function standardizeData(Matrix $new_data = null, Vector $center = null, Vector $scale = null): Matrix
    {
        if ($new_data === null) {
            $X = $this->Xdata;
        } else {
            $this->checkNewData($new_data);
            $X = $new_data;
        }
        $ones_column = MatrixFactory::one($X->getM(), 1);
        
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $center_matrix = $center_matrix ?? $ones_column->multiply(MatrixFactory::create([$this->Xcenter->getVector()]));
        $scale_matrix = $scale_matrix ?? MatrixFactory::diagonal($this->Xscale->getVector())->inverse();

        // scaled data: ($X - μ) / σ
        return $X->subtract($center_matrix)->multiply($scale_matrix);
    }

    private static function columnStdevs(Matrix $M)
    {
        $scaleArray = [];
        for ($i = 0; $i < $M->getN(); $i++) {
            $scaleArray[] = Descriptive::standardDeviation($M->getColumn($i));
        }
        return new Vector($scaleArray);
    }

    /**
     * Regression Through the Origin
     */
    private static function RTO(Matrix $X, Matrix $Y)
    {
        return $X->transpose()->multiply($Y)->scalarDivide($Y->transpose->multiply($Y)->getValue(0, 0));
    }
}
