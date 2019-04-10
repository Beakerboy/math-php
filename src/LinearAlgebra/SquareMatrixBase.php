<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * m x m Matrix
 *
 * This abstract class contains properties and methods that are shared by matricies
 * regardless of the type of data that they contain.
 *
 */
trait SquareMatrixBase
{
    /** @var number Determinant */
    protected $det;
    
    /** @var Matrix Inverse */
    protected $A⁻¹;
    
    /**************************************************************************
     * BASIC MATRIX GETTERs
     *  - getDiagonalElements
     *  - getSuperdiagonalElements
     *  - getSubdiagonalElements
     **************************************************************************/
 
    /**
     * Returns the elements on the diagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getDiagonalElements($A) = [1, 5, 9]
     *
     * @return array
     */
    public function getDiagonalElements(): array
    {
        $diagonal = [];
        for ($i = 0; $i < $this->m; $i++) {
            $diagonal[] = $this->A[$i][$i];
        }
        return $diagonal;
    }

    /**
     * Returns the elements on the superdiagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getSuperdiagonalElements($A) = [2, 6]
     *
     * http://mathworld.wolfram.com/Superdiagonal.html
     *
     * @return array
     */
    public function getSuperdiagonalElements(): array
    {
        $superdiagonal = [];
        for ($i = 0; $i < $this->m - 1; $i++) {
            $superdiagonal[] = $this->A[$i][$i+1];
        }
        return $superdiagonal;
    }

    /**
     * Returns the elements on the subdiagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getSubdiagonalElements($A) = [4, 8]
     *
     * http://mathworld.wolfram.com/Subdiagonal.html
     *
     * @return array
     */
    public function getSubdiagonalElements(): array
    {
        $subdiagonal = [];
        for ($i = 1; $i < $this->m; $i++) {
            $subdiagonal[] = $this->A[$i][$i-1];
        }
        return $subdiagonal;
    }
}
