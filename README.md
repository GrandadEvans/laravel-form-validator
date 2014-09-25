# Laravel Form Validator

[![Build Status](https://travis-ci.org/GrandadEvans/laravel-form-validator.svg?branch=v0.1.2)](https://travis-ci.org/GrandadEvans/laravel-form-validator)

## Usage
From the command line you can call the package with

```
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

```
--rules="username:required:between(6,50):alpha | password:required:min(8)"
```

Each condition that is entered (required, confirmed etc) will be validated against the [available conditions in Laravel docs](http://laravel.com/docs/validation#available-validation-rules).

Once the command is executed a Form is generated and placed in the specified directory (or the default app/Forms).
This package relies on the [Laracasts\Validation](https://github.com/laracasts/Validation) package. To use it:
1. Run the command as above
2. Include the service provider in your app/config/app.php by adding it to your "providers" array:

```
//app/config/app.php
'providers' => array(
    #########
    'Grandadevans\GenerateForm\ServiceProvider\GenerateFormServiceProvider'
);
```

3. Inject and Type hint the generated form into your controller (or where you wish to do your validation)

```php
protected $loginForm;

public function __construct(LoginForm $loginForm)
{
	$this->loginForm = $loginForm;
}
```

4. Try to validate the input with

```php
$this->loginForm->validate(Input::only(['username','password']));
```

## Installation

Install this package through [Composer](https://getcomposer.org).

```js
"require-dev": {
    "grandadevans:laravel-form-validator": "~0.1.0"
}
```
