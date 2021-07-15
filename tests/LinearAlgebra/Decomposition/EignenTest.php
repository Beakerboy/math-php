<?php

namespace MathPHP\Tests\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;

class EigenTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test   Eigen Decomposition invalid property
     * @throws \Exception
     */
    public function testCountDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $eigen = $A->eigenDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $eigen->doesNotExist;
    }
}
