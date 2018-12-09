<?php
namespace MathPHP\Tests\Number;

use MathPHP\Number\BigNumber;
use MathPHP\Exception;

class BigNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     __toString returns the proper string representation of a BigNumber
     * @dataProvider dataProviderForToString
     * @param        string $i
     * @param        string $string
     */
    public function testToString(string $i, string $string)
    {
        $number = new BigNumber($i);
        $this->assertEquals($string, $number->__toString());
        $this->assertEquals($string, (string) $number);
    }

    public function dataProviderForToString(): array
    {
        return [
            ['0', '0'],
            ['1234567890123456789012345678.901','1234567890123456789012345678.901'],
        ];
    }
        
