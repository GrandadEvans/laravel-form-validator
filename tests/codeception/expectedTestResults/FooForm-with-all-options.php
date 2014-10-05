<?php namespace Bar;

use Laracasts\Validation\FormValidator;

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
