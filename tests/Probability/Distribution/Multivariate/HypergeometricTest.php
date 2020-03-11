<?php

namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate\Hypergeometric;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    public function testHypergeometeic() {
        $dist = new Hypergeometric([15, 10, 15]);
        $this->assertEquals(496125 / 3838380, $dist->pfm([2, 2, 2]));
    }
}
