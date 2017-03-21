<?php
namespace Math\LinearAlgebra;

class Tensor
{
    /**
     * The container for the tensor data.
     * It can be a scaler or an array.
     */
    protected $A;
    
    /**
     * An array of tensor dimensions.
     * Scaler: $dimensions = []
     * Vector of length = 4: $dimensions = [4]
     * Matrix of size 4x5: $dimensions = [4,5] (4 rows X 5 columns)
     *
     */
    protected $dimensions = [];
    
    /**
     * The order (rank) of the tensor
     */
    protected $order = 0;
    
    /**
     * Create load the data into the container.
     * Constructs an array of the size of each dimension.
     * Determines the order of the Tensor
     *
     * @todo Error checking. Make sure the tensor is well formatted
     */
    public function __construct($A)
    {
        if (is_numeric($A)) {
            $this->A = $A;
        } else {
            $this->dimensions = measureDimensions($A);
            $this->order = count($this->dimensions);
            $this->A = $A;
        }
    }
    
    /**
     * Iterate through the first sub-array of each array to measure the size of each dimensions.
     */
    private function measureDimensions(array $array)
    {
        $dimensions[] = count($array);
        $done = false;
        $test_array = $array;
        while (is_array($array[0])) {
            $dimensions[] = count($array[0]);
            $test_array = $test_array[0];
        }
        return $dimensions;
    }
    
    /**
     * Setters and Getters
     */
     
    /**
     * Return the array of dimensions
     *
     *@return array
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    /**
     * Return the order (rank) of the Tensor
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * Produces the Tensor product of two tensors
     *
     * @param Tensor $T
     *
     * @return Tensor
     */
    public function product(Tensor $T): Tensor
    {
        $T_dimensions = $T->getDimensions();
        $A_dimensions = $this->dimensions;
        $N_dimensions = array_merge($A_dimensions, $T_dimensions);
        
        // Create New Tensor
        $N = Tensor::zeroes($N_dimensions);
        
        $A_index = 0;
        $T_index = 0;
        
        // Number of elements in the Tensors
        $T_size = array_product($T_dimensions);
        $A_size = array_product($A_dimensions);
        
        for ($i = 0; $i < $A_size; $i++) {
            for ($j = 0; $j < $T_size; $j++) {
                $A_position = $A->getIndexFromElement($i);
                $T_position = $T->getIndexFromElement($j);
                $N_position = array_merge($A_position, $T_position);
                
                $A_value = $A->getValue($A_position);
                $T_value = $T->getValue($T_position);
                $N_value = $A_value * $T_value;
                $N->setValue($N_position, $N_value);
            }
        }
        return $N;
    }
    
    /**
     * Given an integer $n, provide the $nth element in the Tensor.
     *
     * Useful for iterative functions where the sequence of operation
     * is not important, but full coverage is.
     *
     * If the Tensor dimensions are [2, 3]
     * n = 0: [0, 0]
     * n = 1: [1, 0]
     * n = 2: [0, 1]
     * n = 3: [1, 1]
     * n = 4: [0, 2]
     * n = 5: [1, 2]
     */
    public function getIndexFromNumber(int $number)
    {
        // if ($number >= product($this->dimensions) throw exception
        $index = [];
        foreach ($this->dimensions as $dimension) {
            $i = $number % $dimension;
            $index[] = $i;
            $number = ($number - $i) / $dimension;
        }
    }
    /**
     * Contract the tensor by an order of two by summing
     * the diagonal element on the supplied dimensions.
     *
     * This is the Tensor equivalent to the Matrix trace operation
     */
    public function contract(int $n, int $m): Tensor
    {
        //check that the tensor is aware along the specified dimensions
        //check that the tensor order is large enough.
        return;
    }
    
    /**
     * Produce a Tensor with the specified dimensions,
     * with all values initialized to zero.
     * zeroes([2]) = [0, 0]
     * zeroes([2, 3]) = [ [0, 0, 0],
     *                    [0, 0, 0] ]
     *
     * zeroes([2, 3, 2]) =  [ [ [0, 0], [0, 0], [0, 0] ],
     *                        [ [0, 0], [0, 0], [0, 0] ] ]
     *
     * @param array $dimensions: The shape of the tensor to produce
     * @param optional $value  : The initial value to assign to the tesnor
     */
    public static function zeroes(array $dimensions, $value = 0): Tensor
    {
        $A = [];
        $dimensions_reverse = array_reverse($dimensions);
        foreach ($dimensions_reverse as $dimension) {
            for ($i = 0; $i < $dimension; $i++) {
                $A[] = $value;
            }
            $value = $A;
            $A = [];
        }
    }
    
    /**
     * Set the value at the given position
     */
    public function setValue($value, array $position)
    {
        if (count($position) != $this->order) {
            //throw exception. the tensors are different orders
        }
        foreach ($position as $key => $size) {
            if ($size > $this->dimensions[$key]) {
                //Element out of bounds exception
            }
        }
        if (!is_numeric($value)) {
            // We shouldn't be adding arrays or strings to a Tensor, right?
            // throw an exception
        }
        $element = &$this->A;
        foreach ($position as $subarray) {
            $element = &$element[$subarray];
        }
        $element = $value;
    }
    
    /**
     * Get the value at the given position
     */
    public function getValue(array $position)
    {
        $value = $this->A;
        foreach ($positions as $position) {
            $value = $value[$position];
        }
        return $value;
    }
}
