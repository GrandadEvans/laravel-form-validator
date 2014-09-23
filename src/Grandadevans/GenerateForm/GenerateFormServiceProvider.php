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

    public function register()
    {
        $this->app->bind(
            'Grandadevans\GenerateForm\Interfaces\PathInterface',
            'Grandadevans\GenerateForm\Handlers\FilesystemHandler');

        $this->app->make('Grandadevans\GenerateForm\Interfaces\PathInterface');

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
