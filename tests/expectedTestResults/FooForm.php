<?php namespace Bar;

use laracasts\validation;

/**
 *
 * Class Foo
 *
 */
class Foo
{

    /**
     * The array of rules to be processed
     *
     * @var array
     */
    protected $rules=[
        'baz' => [
            'required',
        ],
    
        'qux' => [
            'between(3,6)',
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
