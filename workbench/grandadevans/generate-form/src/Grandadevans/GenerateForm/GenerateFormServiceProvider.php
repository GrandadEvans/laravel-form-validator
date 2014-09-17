<?php namespace Grandadevans\GenerateForm;

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
        Artisan::resolve('Grandadevans\GenerateForm\Command\FormGeneratorCommand');

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
