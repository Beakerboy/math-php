<?php
namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\LinearAlgebra\Eigenvalue;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

/**
 * Singular value decomposition
 *
 * The generalization of the eigendecomposition of a square matrix to an m x n matrix
 * https://en.wikipedia.org/wiki/Singular_value_decomposition
 */
class SVD implements \ArrayAccess
{
    /** @var Matrix m x m orthogonal matrix  */
    private $U;

    /** @var Matrix n x n orthogonal matrix  */
    private $Vt;

    /** @var Matrix m x n diagonal matrix  */
    private $S;
    
    /**
     * SVD constructor
     *
     * @param Matrix $U Orthogonal matrix
     * @param Matrix $S Diagonal matrix
     * @param Matrix $V Orthogonal matrix
     */
    private function __construct(Matrix $U, Matrix $S, Matrix $Vt)
    {
        $this->U = $U;
        $this->S = $S;
        $this->Vt = $Vt;
    }

    /**
     * Get U
     *
     * @return Matrix
     */
    public function getU(): Matrix
    {
        return $this->U;
    }

    /**
     * Get S
     *
     * @return Matrix
     */
    public function getS(): Matrix
    {
        return $this->S;
    }

    /**
     * Get V
     *
     * @return Matrix
     */
    public function getVt(): Matrix
    {
        return $this->Vt;
    }

    public static function decompose(Matrix $M): SVD
    {
        $Mt = $M->transpose();
        $MMt = $M->multiply($Mt);
        $MtM = $Mt->multiply($M);
        
        // m x m orthoganol matrix
        $U = $MMt->eigenvectors(Eigenvalue::JACOBI_METHOD);
        
        // n x n orthoganol matrix
        $Vt = $MtM->eigenvectors(Eigenvalue::JACOBI_METHOD)->transpose();
        
        // Diagonal matrix
        $smallest_matrix = $M->getM() < $M->getN() ? $MMt : $MtM;
        $S = MatrixFactory::create(Single::sqrt($smallest_matrix->eigenvalues(Eigenvalue::JACOBI_METHOD)));
        
        return new SVD($U, $S, $Vt);
    }

    /**
     * Get U, S, or V matrix
     *
     * @param string $name
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     */
    public function __get(string $name): Matrix
    {
        switch ($name) {
            case 'U':
            case 'S':
            case 'Vt':
                return $this->$name;
            default:
                throw new Exception\MatrixException("SVD class does not have a gettable property: $name");
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
            case 'U':
            case 'S':
            case 'Vt':
                return true;
            default:
                return false;
        }
    }

    /**
     * @param mixed $i
     * @return mixed
     */
    public function offsetGet($i)
    {
        return $this->$i;
    }

    /**
     * @param  mixed $i
     * @param  mixed $value
     * @throws Exception\MatrixException
     */
    public function offsetSet($i, $value)
    {
        throw new Exception\MatrixException('SVD class does not allow setting values');
    }

    /**
     * @param  mixed $i
     * @throws Exception\MatrixException
     */
    public function offsetUnset($i)
    {
        throw new Exception\MatrixException('SVD class does not allow unsetting values');
    }
}
