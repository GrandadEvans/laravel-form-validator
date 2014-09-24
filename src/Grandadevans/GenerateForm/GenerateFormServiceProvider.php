<?php namespace Grandadevans\GenerateForm;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class GenerateFormServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the Service provider and the PathInterface binding
	 */
	public function register()
    {
    }

    public function boot()
    {
        // Define the Directory separator as a constant
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

	    // Bind the command
        $this->app->bind('generate:form', function($app) {

            // Tell Laravel which implementation of PathInterface we want to use
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
		return array();
	}

}
