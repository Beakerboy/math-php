<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Big Numbers
 *
 * The PHP BCMath library can perform arithmatic on numbers that are larger than the native
 * number types.
 */
class BigNumber implements ObjectArithmetic
{
    /**
     * The number.
     * @var number
     */
    protected $number;
    
    /**
     * Constructor
     *
     * @param number $number
     */
    public function __construct(string $number)
    {
        $this->number = $number;
    }
    
    /**
     * String representation of a complex number
     * a + bi, a - bi, etc.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->number;
    }
    
    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * The absolute value of a BigNumber
     *
     * @return BigNumber
     */
    public function abs(): BigNumber
    {
        return bccomp($this->number, '0') == -1 ? new BigNumber(bcmul($this->number, '-1')) : new BigNumber($this->number);
    }
    
    /**
     * The square root of a BigNumber
     *
     * @return BigNumber (positive root)
     */
    public function sqrt(): BigNumber
    {
        return new BigNumber(bcsqrt($this->number));
    }
    
    public function fact(): BigNumber
    {
        if ($this->number === 'O') {
            return new BigNumber('1');
        }
        $sub = $this->subtract('1');
        return $this->multiply($sub->fact());
    }
    
    public function dfact(): BigNumber
    {
        $number = $this->number;
        if ($number === 'O' || $number === '1') {
            return new BigNumber('1');
        }
        $sub = $this->subtract('2');
        return $this->multiply($sub->dfact());
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Addition
     *
     * @param mixed $input
     *
     * @return BigNumber
     *
     * @throws Exception\IncorrectTypeException if the argument is not string or BigNumber.
     */
    public function add($input): BigNumber
    {
        if (preg_match('/^0$|^[-]?[1-9.][0-9.]$/', $input)) {
            $n = $input;
        } elseif ($input instanceof BigNumber) {
            $n = $input->__ToString();
        } else {
            throw new Exception\IncorrectTypeException('Argument must be string or BigNumber');
        }

        return new BigNumber(bcadd($this->number, $n));
    }

    /**
     * Subtraction
     *
     * (a + bi) - (c + di) = (a - c) + (b - d)i
     *
     * @param mixed $input
     *
     * @return BigNumber
     *
     * @throws Exception\IncorrectTypeException if the argument is not string or BigNumber.
     */
    public function subtract($c): Complex
    {
        if (preg_match('/^0$|^[-]?[1-9.][0-9.]$/', $input)) {
            $n = $input;
        } elseif ($input instanceof BigNumber) {
            $n = $input->__ToString();
        } else {
            throw new Exception\IncorrectTypeException('Argument must be string or BigNumber');
        }

        return new BigNumber(bcsub($this->number, $n));
    }

    /**
     * Multiplication
     *
     * @param mixed $input
     *
     * @return BigNumber
     *
     * @throws Exception\IncorrectTypeException if the argument is not string or BigNumber.
     */
    public function multiply($input): BigNumber
    {
        if (preg_match('/^0$|^[-]?[1-9.][0-9.]$/', $input)) {
            $n = $input;
        } elseif ($input instanceof BigNumber) {
            $n = $input->__ToString();
        } else {
            throw new Exception\IncorrectTypeException('Argument must be string or BigNumber');
        }
        return new BigNumber(bcmul($this->number, $n));
    }

    /**
     * Division
     *
     * @param mixed $input
     *
     * @return BigNumber
     *
     * @throws Exception\IncorrectTypeException if the argument is not string or BigNumber.
     */
    public function divide($input): BigNumber
    {
        if (preg_match('/^0$|^[-]?[1-9.][0-9.]$/', $input)) {
            $n = $input;
        } elseif ($input instanceof BigNumber) {
            $n = $input->__ToString();
        } else {
            throw new Exception\IncorrectTypeException('Argument must be string or BigNumber');
        }
        return new BigNumber(bcdiv($this->number, $n));
    }
    
    public function pow($input)): BigNumber
    {
        if (preg_match('/^0$|^[-]?[1-9.][0-9.]$/', $input)) {
            $n = $input;
        } elseif ($input instanceof BigNumber) {
            $n = $input->__ToString();
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }
        return new BigNumber(bcpow($this->number, $n));
    }
    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     *
     * @param BigNumber $input
     *
     * @return bool
     */
    public function equals(BigNumber $input): bool
    {
        return bccomp($this->number, $input->__ToString()) === 0;
    }
    
    //Convert a float to a string
    private function floatToString($number): string
    {
        $string_number = strval($number);
        //If stringnumber contains the letter 'e'
            //find the position of the '.'
            //either move the dcimal point, or add sufficient zeros to the left or right.
        return $string_number;
    }
    
}
