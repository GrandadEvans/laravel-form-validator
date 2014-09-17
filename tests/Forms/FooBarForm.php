<?php namespace grandadevans;

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
            'unique',
            'required',
            'min(5)',
        ],
    
        'qux' => [
            'required',
            'email',
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
     *
     * @var array
     */
    protected $customMessages = [];

}
