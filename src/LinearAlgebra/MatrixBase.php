<?php

namespace MathPHP\LinearAlgebra;
/**
 * m x n Matrix
 */
abstract class MatrixBase implements \ArrayAccess, \JsonSerializable
{
    /** @var int Number of rows */
    protected $m;

    /** @var int Number of columns */
    protected $n;

    /** @var array Matrix array of arrays */
    protected $A;
}
