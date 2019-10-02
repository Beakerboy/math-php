<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Exception;
use MathPHP\Functions\BaseEncoderDecoder;
use MathPHP\Number\ArbitraryInteger;

class BaseEncoderDecoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dataProviderForTestToBase
     */
    public function testToBase(string $int, int $base, string $expected)
    {
        $int = new ArbitraryInteger($int);
        $this->expectEquals($expected, BaseEncoderDecoder::toBase($int, $base));
    }

    public function dataProviderForTestToBase()
    {
        return [
           ['0xf', 16, 'f'],
        ];
    }

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
        $string =  BaseEncoderDecoder::toBase($int, $base);
    }
}
