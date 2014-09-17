<?php namespace grandadevans\Forms; 

use laracasts\validation;

/**
 *
 * Class FooBar
 *
 */
class FooBar
{

   /**
    * The array of rules to be processed
    *
    * @var array
    */
   protected $rules=[
       'bar' => [
           'required', 
           'between(4,6)', 
           'digits', 
       ],
   
       'qux' => [
           'required', 
           'email', 
           'confirmed', 
       ],
   ];


   /**
    * The array of input fields to validate
    *
    * @var array
    */
   protected $input = [];


    /**
     * An array of custom messages for the validation failures
     * @var array
     */
    protected $customMessages = [];


}
