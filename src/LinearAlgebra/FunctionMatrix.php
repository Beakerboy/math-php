<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
 /**
  * Class: FunctionMatrix
  */
class FunctionMatrix extends Matrix
{
    /**
     * Function: evaluate
     * Evaluate the functions with the given parameters and
     * return the result as a matrix.
     *
     * @param array $params
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public function evaluate(array $params): Matrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $func = $this->A[$i][$j];
                $R[$i][$j] = $func($params);
            }
        }
        return MatrixFactory::create($R);
    }
}
