<?php namespace Baz;

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
        'baz' => 'required|email',
        'qux' => 'between(3,6)',
    ];
}
