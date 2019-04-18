<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Number\ObjectArithmatic;

interface MatrixInterface extends ObjectArithmatic
{
    /**
     * What type of data does the matrix contain
     *
     * @return the type of data in the Matrix
     */
    public function getObjectType(): string;
}
