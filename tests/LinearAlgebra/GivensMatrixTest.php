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
}
