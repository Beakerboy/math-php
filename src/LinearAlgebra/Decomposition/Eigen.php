<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

class Eigen extends DecompositionBase
{
    public function __construct(Matrix $V, Vector $D)
    {
        $this->V = $V;
        $this->D = $D;
    }

    public static function decompose(Matrix $M): Eigen
    {
        $eigenvalues = [];
        $vectors = [];
        for ($i = 0; $i < $M->getM(); $i++) {
            list ($eigenvalue, $eigenvector) = self::powerIteration($A);
            $eigenvalues[] = $eigenvalue;
            $vectors[] = $eigenvector->transpose()->getMatrix()[0]; // Adding as a new row
            $M = $M->subtract($eigenvector->multiply($eigenvector->transpose())->scalarMultiply($eigenvalue));
        }
        $D = new Vector($eigenvalues);
        $V = MatrixFactory::create($vectors)->transpose();
        return new Eigen($V, $D);
    }

    public static function powerIteration(Matrix $A, int $iterations = 1000): array
    {
        self::checkMatrix($A);
        
        $b    = MatrixFactory::random($A->getM(), 1);
        $newμ = 0;
        $μ    = -1;
        while (!Support::isEqual($μ, $newμ)) {
            if ($iterations <= 0) {
                throw new Exception\FunctionFailedToConvergeException("Maximum number of iterations exceeded.");
            }
            $μ    = $newμ;
            $Ab   = $A->multiply($b);
            $b    = $Ab->scalarDivide($Ab->frobeniusNorm());
            $newμ = $b->transpose()->multiply($A)->multiply($b)->get(0, 0) / $b->transpose()->multiply($b)->get(0, 0);
            $iterations--;
        }
        return [$newμ, $b];
    }

    /**
     * Get V or D
     *
     * @param string $name
     *
     * @return Matrix
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
    /**************************************************************************
     * ArrayAccess INTERFACE
     **************************************************************************/
    /**
     * @param mixed $i
     * @return bool
     */
    public function offsetExists($i): bool
    {
        switch ($i) {
            case 'V':
            case 'D':
                return true;
            default:
                return false;
        }
    }
}
