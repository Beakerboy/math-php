?php

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

    /**
     * @test   Eigen Decomposition ArrayAccess
     * @throws \Exception
     */
    public function testEigenDecompositionArrayAccess()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $eigen = $A->eigenDecomposition();

        // When
        $V = $crout['V'];
        $D = $crout['D'];

        // Then
        $this->assertEquals($eigen->V, $V);
        $this->assertEquals($eigen->D, $D);
    }

    /**
     * @test   Eigen Decomposition ArrayAccess invalid offset
     * @throws \Exception
     */
    public function testeigenDecompositionArrayAccessInvalidOffset()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $eigen = $A->eigenDecomposition();

        // Then
        $this->assertFalse(isset($eigen['doesNotExist']));
    }

    /**
     * @test   Eigen Decomposition ArrayAccess set disabled
     * @throws \Exception
     */
    public function testEigenDecompositionArrayAccessSetDisabled()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $eigen = $A->eigenDecomposition();

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $eigen['V'] = $A;
    }

    /**
     * @test   Eigen Decomposition ArrayAccess unset disabled
     * @throws \Exception
     */
    public function testEigenDecompositionArrayAccessUnsetDisabled()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $eigen = $A->eigenDecomposition();

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        unset($eigen['V']);
    }
}
