<?php namespace Grandadevans\GenerateForm\ServiceProvider;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

/**
 * Service Provider for Grandadevans\laravel-form-validator
 *
 * Class GenerateFormServiceProvider
 *
 * @author  john Evans<john@grandadevans.com>
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 * @package Grandadevans\laravel-form-validator
 */
class GenerateFormServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the Service provider and the PathInterface binding
	 */
	public function register(){}

    /**
     *
     */
    public function boot()
    {
        // Define the Directory separator as a constant
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

	    // Bind the command
        $this->app->bind('generate:form', function($app) {

            // Inject the form generator into the command
            $formGenerator = new FormGenerator;
            return new FormGeneratorCommand($formGenerator);
        });

        $this->commands('generate:form');
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'Laracasts\Validation\ValidationServiceProvider'
		];
	}

}
