<?php
namespace MathPHP\LinearAlgebra;
namespace MathPHP\Functions\Map\Multi;
namespace MathPHP\Functions\Map\Single;

class Tensor
{
    /**
     * The container for the tensor data.
     * It can be a scaler or an array.
     */
    protected $A;
    
    /**
     * An array that indicates which indicies are covariant.
     *
     * Tensor Tᵝᵩᵪᵞ would have $contravariant_indice = [2,3]
     * (should this be [1,2]?)
     */
    protected $covariant_indices = [];
    
    /**
     * An array that indicates which indicies are covariant.
     *
     * Tensor Tᵝᵩᵪᵞ would have $contravariant_indice = [1,4]
     * (should this be [0,3]?)
     */
    protected $contravariant_indices = [];
    
    /**
     * An array that indicates which indicies are covariant or contravariant.
     * 
     * Tensor Tᵝᵩᵪᵞ would have $cov_or_con = [1,-1,-1,1]
     */
    protected $cov_or_con;
    
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
     * @param array or numeric $A 
     * @param array $cov_or_con An ordered list of which indices are covariant, contravatiant is +1, covariant is -1
     *
     * @todo Error checking. Make sure the tensor is well formatted
     */
    public function __construct($A, array $cov_or_con)
    {
        if (is_numeric($A)) {
            $this->A = $A;
        } else {
           
            $this->dimensions = measureDimensions($A);
            $this->order = count($this->dimensions);
            if (count($cov_or_con) != $this->order){
              //Exception all dimensions need to be defined.   
            }
            //Each value in $cov_or_con shall be +1 or -1. If not, throw exeption
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
     * Return the list of covariant and contrvariant indicies
     *
     * @return array
     */
    public function getCovOrCon()
    {
        return $this->cov_or_con;
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
        $N_cov_or_con = array_merge($A->cov_or_con, $T->getCovOrCon());
        $N_dimension_variance = Multi::multiply($N_dimensions, $N_cov_or_con); 
        // Create New Tensor
        $N = Tensor::zeroes($N_dimension_variance);
        
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
        // Check that one index in covariant and one is contravariant.
        // Check that count($this->dimensions) >= max($n,$m) 
        $N_dimensions = array_splice(array_splice($this->dimensions, max($n, $m), 1), min($m, $n), 1);
        $N_cov_or_con = array_splice(array_splice($this->cov_or_con, max($n, $m), 1), min($m, $n), 1);
        $N_dimension_variance = Multi::multiply($N_dimensions, $N_cov_or_con);
        
        // Create New Tensor
        $N = Tensor::zeroes($N_dimension_variance);
        for ($i = 0; $i < array_product($this->dimensions); $i++) {
            
          // For each element in $N, sum the values of $this where $m = $n and the rest of the indices match the position in N.
          // ie Tᵝᵩᵪᵞ->contract(1,2) = Nᵪᵞ = T¹₁ᵪᵞ + T²₂ᵪᵞ + T³₃ᵪᵞ ... for all χ and γ.
          $N_index = $n->getIndexFromNumber($i);
          $sum = 0;
          for ($j = 0; $j < min($this->dimensions[$n-1], $this->dimensions[$m-1]); $j++) {
              // Find the index position in $this having the same position as the N index, but moving
              // along the diagonal of n and m.
              $A_index = array_splice($N_index, min($n, $m), 0, $j);
              $A_index = array_splice($A_index, max($n, $m), 0, $j);
              $sum += $this->getValue($A_index);
          }
          $N->setValue($sum, $N_index);
        }
        return $N;
    }
    
    /**
     * Produce a Tensor with the specified dimensions,
     * with all values initialized to zero, or some other specified value.
     * zeroes([2]) = [0, 0]
     * zeroes([2, 3]) = [ [0, 0, 0],
     *                    [0, 0, 0] ]
     *
     * zeroes([2, 3, 2]) =  [ [ [0, 0], [0, 0], [0, 0] ],
     *                        [ [0, 0], [0, 0], [0, 0] ] ]
     *
     * The sign of the dimension idicates whether the particular dimension is
     * covariant or contrvariant. (1 = contravariant, -1 = covariant)
     * @param array $dimensions: The shape of the tensor to produce
     * @param optional $value  : The initial value to assign to the tesnor
     */
    public static function zeroes(array $dimensions_variance, $value = 0): Tensor
    {
        $A = [];
        $dimensions = Single::abs($dimensions_variance);
        $cov_or_con = Multi::divide($dimensions, $dimensions_variance);
        
        // We need to reverse the dimensions in order to build the Tensor from the inside out.
        $dimensions_reverse = array_reverse($dimensions);
        foreach ($dimensions_reverse as $dimension) {
            for ($i = 0; $i < $dimension; $i++) {
                $A[] = $value;
            }
            $value = $A;
            $A = [];
        }
       return new Tensor($value, $cov_or_con);
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
