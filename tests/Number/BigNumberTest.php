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
    
    /**
     * @testCase     add returns the proper sum
     * @dataProvider dataProviderForAdd
     * @param        string $a
     * @param        string $b
     * @param        string $result
     */
    public function testAddition(string $a, string $b, string $result)
    {
        $objecta = new BigNumber($a);
        $objectb = new BigNumber($b);
        $object_result = $objecta->add($objectb);
        $this->assertEquals($result, $object_result->__ToString());
    }
    
    public function dataProviderForAdd()
    {
        return [
            ['0', '0', '0'],
            ['0', '738474', '738474'],
            ['284847', '4526948', '4811895'],
            ['-3637', '3637', '0'],
            ['-173', '-174', '-347']
        ];
    }
}
