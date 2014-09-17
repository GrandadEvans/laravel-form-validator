<?php namespace Grandadevans\GenerateForm;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
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
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
//        \Artisan::resolve('\Grandadevans\GenerateForm\Command\FormGeneratorCommand');

    }

    public function boot()
    {
        $this->app->bind('generate:form', function($app) {
            return new FormGeneratorCommand();
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
