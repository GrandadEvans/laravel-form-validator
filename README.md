# Laravel Form Validator

[![Build Status](https://travis-ci.org/GrandadEvans/generate-forms.svg?branch=master)](https://travis-ci.org/GrandadEvans/generate-forms)

## Contents
 * [Introduction](https://github.com/GrandadEvans/generate-forms/tree/master#introduction)
 * [Installation](https://github.com/GrandadEvans/generate-forms/tree/master#installation)
 * [Usage](https://github.com/GrandadEvans/generate-forms/tree/master#usage)
 * [Example](https://github.com/GrandadEvans/generate-forms/tree/master#example)
 
## Introduction
After using [Jeffrey Way](https://github.com/JeffreyWay)'s [Generator](https://github.com/JeffreyWay/Laravel-Generators) and his [Validator](https://github.com/laracasts/Validation) package for a while now I got fed up of copying and pasting the contents of the form validation files so thought I'd create my own version.

### What will this do?
This package will create a form containing a list or rules for the validation package to validate.

### Why not just folk the generators package?
Well I still only have 3 or 4 clients as a Freelance Web Developer and this gives me the perfect ground to show my coding skills off. That way when people ask me if I have any work I can show them it doesn't seem like I'm making excuses when I mention "white labelling" and "confidentiality agreements".

## Installation
Install this package through [Composer](https://getcomposer.org).

```js
"require-dev": {
    "grandadevans:laravel-form-validator": "~0.1.0"
}
```
Then include the service provider in your app/config/app.php by adding it to your "providers" array:

```php
/*
 * app/config/app.php
 */
'providers' => array(
    #########
    'Grandadevans\GenerateForm\GenerateFormServiceProvider'
);
```


## Usage
From the command line you can call the package with

```bash
php artisan generate:form
```

From the root directory of your Laravel installation.

### Required Argument
There is only one required argument and that is the **name** of the form

### Options
 * **--dir**       This is the directory in which the form will be saved to *(defaults to app/Forms)*. Please make sure that the directory already exists.
 * **--rules**     This is a string representation of the rules to be used *(see below).*
 * **--namespace** This is the namespace that will be given to the Form.

#### The Rules Option
This is where the command really comes into it's own.
The rules string should be made up of the following

1.  The name of the input field to validate followed by a colon ( **:** )
2.  A list of the conditions that the input is to validate against. Each condition being separated by another colon ( **:** )
3.  If you wish to validate another field then separate them with a pipe ( **|** ) and carry on.

**Example**: If I wanted to validate a typical login form containing a username and password field I would set the rules option as follows

```bash
php artisan generate:form --rules="username:required:between(6,50):alpha | password:required:min(8)"
```

Each condition that is entered (*required*, *confirmed* etc) will be validated against the [available conditions in Laravel docs](http://laravel.com/docs/validation#available-validation-rules).

Once the command is executed a Form is generated and placed in the specified directory (or the default app/Forms).

Inject and Type hint the generated form into your controller (or where you wish to do your validation)

```php
protected $loginForm;

public function __construct(LoginForm $loginForm)
{
	$this->loginForm = $loginForm;
}
```

#### Try to validate the input with

```php
$this->loginForm->validate(Input::only(['username','password']));
```

## Example
Let's say I want to create the above mentioned login form

### Step 1: Create the form
```bash
php artisan generate:form --rules="username:required:between(6,50):alpha | password:required:min(8)"
```

I can then view the form at `app/Forms/FooForm.php`.

```php

<?php

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
        'username' => 'required|between(6,50)|alpha',
        'password' => 'required||min(8)',
    ];
}
```

### Step 2: Inject the form into your controller/model
