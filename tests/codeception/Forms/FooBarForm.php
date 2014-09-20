<?php namespace grandadevans;

use laracasts\validation;

/**
 *
 * Class FooBar
 *
 */
class FooBar extends FormValidator {

    /**
     * The array of rules to be processed
     *
     * @var array
     */
    protected $rules=[
        'bar' => 'uniquerequiredmin(5)',
        'qux' => 'requiredemail',
    ];
}
