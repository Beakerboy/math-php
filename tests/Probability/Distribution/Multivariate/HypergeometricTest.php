<?php

namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate\Hypergeometric;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    public function testHypergeometric()
    {
        $dist = new Hypergeometric([15, 10, 15]);
        $this->assertEquals(496125 / 3838380, $dist->pmf([2, 2, 2]));
    }

    /**
     * @dataProvider dataProviderForConstructorExceptions
     */
    public function testConstructorException($quantities)
    {
        $this->expectException(BadDataException::class);
        $dist = new Hypergeometric($quantities);
    }

    public function dataProviderForConstructorExceptions()
    {
        return [
            'float' => [
                [.5, 1, 6],
            ],
            'string' => [
                [10, '1', 6],
            ],
            'less than one'=> [
                [0, 1, 6],
            ]
        ];
    }
}
