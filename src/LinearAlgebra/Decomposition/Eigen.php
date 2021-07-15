<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\Functions\Support;
use MathPHP\LinearAlgebra\NumericMatrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

class Eigen extends Decomposition
{
    protected $D;
    protected $V;

    public function __construct(NumericMatrix $V, Vector $D)
    {
        $this->V = $V;
        $this->D = $D;
    }

    public function getD()
    {
        return $this->D;
    }

    public function getV()
    {
        return $this->V;
    }

    public static function decompose(NumericMatrix $A): Eigen
    {
        self::checkMatrix($A);
        $original = $A;
        $eigenvalues = [];
        $vectors = [];
        for ($i = 0; $i < $A->getM() - 1; $i++) {
            list ($eigenvalue, $eigenvector) = self::powerIteration($A);
            $eigenvector = MatrixFactory::create($eigenvector);
            $eigenvalues[] = $eigenvalue;
            $A = $A->subtract($eigenvector->multiply($eigenvector->transpose())->scalarMultiply($eigenvalue));
            $vectors[] = $eigenvector->transpose()->getMatrix()[0]; // Adding as a new row
        }
        // The matrix trace equals the sum of the eigenvalues. We can avoid using iteration to find the final value.

        $eigenvalues[] = $original->trace() - array_sum($eigenvalues);
        $D = new Vector($eigenvalues);
        // if ($duplicate_eigenvalues) {
        //    throw new Exception\BadDataException('Matrix is not diagonalizable');
        //}
        $V = MatrixFactory::create($vectors)->transpose();
        return new Eigen($V, $D);
    }

    /**
     * Verify that the matrix is diagonalizable
     *
     * @param NumericMatrix $A
     *
     * @throws Exception\BadDataException if the matrix is not diagonalizable
     */
    private static function checkMatrix(NumericMatrix $A)
    {
        if (!$A->isSquare() || $A->isNilpotent()) {
            throw new Exception\BadDataException('Matrix is not diagonalizable');
        }
    }

    /**
     * Power Iteration
     *
     * The recurrence relation:
     *         Abₖ
     * bₖ₊₁ = ------
     *        ‖Abₖ‖
     *
     * will converge to the dominant eigenvector,
     *
     * The corresponding eigenvalue is calculated as:
     *
     *      bₖᐪAbₖ
     * μₖ = -------
     *       bₖᐪbₖ
     *
     * https://en.wikipedia.org/wiki/Power_iteration
     *
     * @param NumericMatrix $A
     * @param int           $iterations max number of iterations to perform
     *
     * @return array        most extreme eigenvalue and eigenvector
     *
     * @throws Exception\BadDataException if the matrix is not square
     * @throws Exception\MathException
     */
    public static function powerIteration(NumericMatrix $A, int $iterations = 1000): array
    {
        self::checkMatrix($A);

        $initial_iter = $iterations;
        do {
            $b = MatrixFactory::random($A->getM(), 1);
        } while ($b->frobeniusNorm() == 0);
        $b = $b->scalarDivide($b->frobeniusNorm());  // Scale to a unit vector

        $newμ      = 0;
        $μ         = -1;
        $max_rerun = 2;
        $rerun     = 0;
        $max_ev    = 0;
        $max_b = [];
        while ($rerun < $max_rerun) {
            while (!Support::isEqual($μ, $newμ)) {
                if ($iterations <= 0) {
                    throw new Exception\FunctionFailedToConvergeException("Maximum number of iterations exceeded.");
                }

                $μ  = $newμ;
                $Ab = $A->multiply($b);
                while ($Ab->frobeniusNorm() == 0) {
                    $Ab = MatrixFactory::random($A->getM(), 1);
                }

                $b    = $Ab->scalarDivide($Ab->frobeniusNorm());
                $newμ = $b->transpose()->multiply($A)->multiply($b)->get(0, 0);
                $iterations--;
            }

            if (abs($newμ) > abs($max_ev)) {
                $max_ev = $newμ;
                $max_b = $b->getMatrix();
            }

            // Perturb the eigenvector and run again to make sure the same solution is found
            $newb = $b->getMatrix();
            for ($i = 0; $i < \count($newb); $i++) {
                $newb[$i][0] = $newb[1][0] + \rand() / 10;
            }
            $b    = MatrixFactory::create($newb);
            $b    = $b->scalarDivide($b->frobeniusNorm());  // Scale to a unit vector
            $newμ = 0;
            $μ    = -1;

            $rerun++;
            $iterations = $initial_iter;
        }

        return [$max_ev, $max_b];
    }

    /**
     * Get V or D
     *
     * @param string $name
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'V':
            case 'D':
                return $this->$name;
            default:
                throw new Exception\MatrixException("QR class does not have a gettable property: $name");
        }
    }
}
