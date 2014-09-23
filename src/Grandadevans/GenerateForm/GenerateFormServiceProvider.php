<?php namespace Grandadevans\GenerateForm;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
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

    }

    public function boot()
    {

        $this->app->bind('generate:form', function($app) {

	        $attributeHandler = new \Grandadevans\GenerateForm\Handlers\AttributeHandler;
	        $pathHandler =      new \Grandadevans\GenerateForm\Handlers\PathHandler;
	        $ruleBuilder =      new \Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
	        $formGenerator =    new \Grandadevans\GenerateForm\FormGenerator\FormGenerator;
	        return new FormGeneratorCommand(
		        $attributeHandler,
		        $pathHandler,
		        $ruleBuilder,
		        $formGenerator
	        );
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
