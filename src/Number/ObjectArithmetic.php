<?php
namespace MathPHP\Number;

/**
 * Interface: ObjectArithmetic 
 * Defines the necessary arithmetic function object need to
 * support to perfom mathematical function on the object.
 */
interface ObjectArithmetic
{
    /**
     * Function: add
     * Add two objects together
     *
     * @param mixed $object_or_scalar the value to be added
     *
     * @return ObjectArithmetic sum.
     */
    public function add($object_or_scalar);

    /*
     * Function: subtract
     * Subtract one objects from another
     *
     * @param mixed $object_or_scalar the value to be subtracted
     *
     * @return ObjectArithmetic result.
     */
    public function subtract($object_or_scalar);

    /*
     * Function: multiply
     * Multiply two objects together
     *
     * @param mixed $object_or_scalar value to be multiplied
     *
     * @return ObjectArithmetic product.
     */
    public function multiply($object_or_scalar);
}
