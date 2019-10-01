<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Functions\BaseEncoderDecoder;
use MathPHP\Number\ArbitraryInteger;

class BaseEncoderDecoderTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test     toBase throws an exception when base>256
     * @throws   BadParameterException
     */
    public function testInvalidToBaseException()
    {
        // Given
        $base = 300;
        $int =  new ArbitraryInteger('123456');
        // Then
        $this->expectException(Exception\BadParameterException::class);
        // When
        $string =  BaseEncoderDecoder::toBase($base);
    }
}
