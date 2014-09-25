<?php namespace Bar;

use laracasts\validation;

/**
 *
 * Class FooForm
 *
 */
class FooForm extends FormValidator {

    /**
     * The array of rules to be processed
     *
     * @var array
     */
    protected $rules=[
        'baz' => 'required|email',
        'qux' => 'between(3,6)',
    ];
}
