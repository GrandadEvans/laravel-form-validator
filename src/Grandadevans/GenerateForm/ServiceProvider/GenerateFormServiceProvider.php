<?php namespace Grandadevans\GenerateForm\ServiceProvider;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Grandadevans\GenerateForm\Handlers\UserFeedbackHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

/**
 * Service Provider for Grandadevans\laravel-form-validator
 *
 * Class GenerateFormServiceProvider
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class GenerateFormServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * Register the Service provider and the PathInterface binding
	 *
	 * Although the form generator could do with being loaded in boot
	 * I need the validator service provider to be put through register so that I can bind it's
	 * interface straight away
	 */
	public function register()
	{
        $this->defineDirectorySeparator();

        $this->bindValidatorsInterface();

        $this->bindTheFormGenerator();

		$this->registerTheCommand();
	}


    /**
     * Required boot option but not used
     */
    public function boot()
    {
	    $this->package('Grandadevans/GenerateForm');
    }


	/**
	 * The unused provides array
	 *
	 * @return array
	 */
	public function provides()
	{
	}


    /**
     * Define the DS constant to the directory separator of the user
     */
    private function defineDirectorySeparator()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
    }


    /**
     * We need to bind the Validator package's service provider's bindings for it, otherwise: we would need to
     * instruct the user to add it's service provider to their app.php as well
     */
    private function bindValidatorsInterface()
    {
        $this->app->bind(
            'Laracasts\Validation\FactoryInterface',
            'Laracasts\Validation\LaravelValidator'
        );
    }


    /**
     * Bind the Grandadevans\GenerateForm and return the command
     */
    public  function bindTheFormGenerator()
    {
        $this->app['generate:form'] = $this->app->share(function ($app) {

            // Inject the form generator into the command
            $formGenerator       = new FormGenerator;
            $userFeedbackHandler = new UserFeedbackHandler;

            return new FormGeneratorCommand($formGenerator, $userFeedbackHandler);
        });
    }


	private function registerTheCommand()
	{
		$this->commands('generate:form');
	}
}
