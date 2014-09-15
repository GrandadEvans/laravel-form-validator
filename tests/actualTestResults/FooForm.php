<?php

namespace Bar;

use Laracasts\Validation\FormValidator;

class FooForm extends FormValidation {

        protected $rules = array(
        "baz" => array(
            "required",        
        ),
        "qux" => array(
            "between(3,6)",        
        ),
    );

}
