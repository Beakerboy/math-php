<?php

namespace MathPHP\Number;

class ArbitraryInteger implements ObjectArithmetic
{

    private $object;

    public function __construct($int)
    {
        if (is_object($int)) {
            $this->object = $int;
        } else {
            if (extension_loaded('gmp')) {
                $this->object = new Implementations\GMP($int);
            } else {
                $this->object = new Implementations\NativePHP($int);
            }
        }
    }

    public static function create($number)
    {
        return new ArbitraryInteger($number);

    public function __toString()
    {
        return $this->object->__toString();
    }

    public function fact()
    {
        return new ArbitraryInteger($this->object->fact());
    }

    public function add($number)
    {
        return new ArbitraryInteger($this->object->add($number));
    }

    public function subtract($number)
    {
        return new ArbitraryInteger($this->object->subtract($number));
    }

    public function multiply($number)
    {
        return new ArbitraryInteger($this->object->multiply($number));
    }

    public function intdiv($number)
    {
        return new ArbitraryInteger($this->object->divide($number));
    }

    public function mod($number)
    {
        return new ArbitraryInteger($this->object->mod($number));
    }

    public function pow($number)
    {
        return new ArbitraryInteger($this->object->pow($number));
    }

    public function equals($number)
    {
        return $this->object->equals($number);
    }

    public function lessThan($number)
    {
        return $this->object->lessThan($number);
    }

    public function greaterThan($number)
    {
        return $this->object->greaterThan($number);
    }
}
