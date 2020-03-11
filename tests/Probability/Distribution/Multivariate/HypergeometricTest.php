<?php

namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate\Hypergeometric;
use MathPHP\Exception;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test.        pmf
     * @dataProvider dataProviderForTestHypergeometric
     */
    public function testHypergeometric(array $quantities, array $picks, $expected)
    {
        $dist = new Hypergeometric($quantities);
        $this->assertEquals($expected, $dist->pmf($picks));
    }

    /**
     * @return array
     */
    public function dataProviderForTestHypergeometric()
    {
        return [
            [
                [15, 10, 15],
                [2, 2, 2],
                496125 / 3838380,
            ],
        ];
    }

    /**
     * @test         pmf
     * @dataProvider dataProviderForConstructorExceptions
     */
    public function testConstructorException($quantities)
    {
        $this->expectException(Exception\BadDataException::class);
        $dist = new Hypergeometric($quantities);
    }

    /**
     * @return array
     */
    public function dataProviderForConstructorExceptions()
    {
        return [
            'float' => [
                [.5, 1, 6],
            ],
            'string' => [
                [10, '1', 6],
            ],
            'less than one' => [
                [0, 1, 6],
            ]
        ];
    }

  /**
     * @test         __construct 
     * @dataProvider dataProviderForPmfExceptions
     */
    public function testPmfException($quantities)
    {
        $this->expectException(Exception\BadDataException::class);
        $dist = new Hypergeometric([10, 10, 10]);
    }

    /**
     * @return array
     */
    public function dataProviderForPmfExceptions()
    {
        return [
            'float' => [
                [.5, 1, 6],
            ],
            'string' => [
                [10, '1', 6],
            ],
            'less than zero' => [
                [-1, 1, 6],
            ],
            'too many' => [
                [11, 1, 6],
            ],
            'mismatched' => [
                [-1, 6],
            ],
        ];
    }

}
