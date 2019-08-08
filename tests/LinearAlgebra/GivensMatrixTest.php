<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception\OutOfBoundsException;
use MathPHP\LinearAlgebra\MatrixFactory;

class GivensMatrixTest extends \PHPUnit\Framework\TestCase
{
    public function testException()
    {
        $this->expectException(OutOfBoundsException::class);
        // When
        $matrix = MatrixFactory::givens(2, 3, \M_PI, 2);
    }

    public function testGivensMatrix($m, $n, $angle, $size, $expected)
    {
    }

    public function dataProviderForTestGivensMatrix()
    {
        return [
            [
                0, 1, \M_PI, 2, 
                [[-1, 0],
                 [0, -1]]
            ],
            [
                1, 2, \M_PI / 4, 3, 
                [[\M_SQRT1_2, 0,  -1 * \M_SQRT1_2],
                 [0,          1,  0],
                 [\M_SQRT1_2, 0, \M_SQRT1_2]],
            ],
        ];
    }
    
}
