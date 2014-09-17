<?php namespace Bar;

use laracasts\validation;

/**
 *
 * Class Foo
 *
 */
class Foo extends FormValidator {

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
