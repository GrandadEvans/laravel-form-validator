<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Mustache_Engine;
use \ClassPreloader\Command;


/**
 * The Output Builder for Grandadevans\laravel-form-validator
 *
 * Class OutputBuilder
 *
 * @todo    Implement the Filesystem class instead of file_get and file_put_contents
 * @todo    Inject the instance of Mustache
 *
 * @author  john Evans<john@grandadevans.com>
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 * @package Grandadevans\laravel-form-validator
 */
class OutputBuilder {

    /**
     * The result string to return
     *
     * @var string
     */
    public $returnResult = 'pass';

	/**
     * The instance of Mustache
     *
     * @var Mustache
     */
    public $mustache;

    /**
     * The Final path of the form
     *
     * @var string
     */
    public $formPath;


	/**
     * Accept the params and kick out the output
     *
     * Accept the rules, className, namespaceName and path of the form and write the requested file
     *
     * @param array  $rules
     * @param string $className
     * @param null   $namespace
     * @param string $formPath
     */
    public function build($rules, $className, $namespace = null, $formPath)
    {
        $this->formPath = $formPath;

        $this->setMustache();

	    $renderedOutput = $this->renderTemplate([
            'rules'     => $rules,
            'namespace' => $namespace,
            'className' => $className
        ]);

        // Try and write the file
        if ( ! $this->writeTemplate($renderedOutput, $formPath)) {
	        $this->returnResult = 'fail';
        }
    }


    /**
     * Called externally this gets the results after the write had been attempted
     *
     * @return array
     */
    public function getReturnDetails()
    {
        return [
            'result' => $this->returnResult,
            'fullFormPath' => $this->formPath
            ];
    }


    /**
     * Render the Template using the instance of Mustache and return the results
     *
     * @var     array   $args
     *
     * @return  string
     */
    private function renderTemplate($args)
    {
        $contents = $this->getTemplateContents();

        return $this->mustache->render($contents, $args);
    }


    /**
     * Try to write the rendered output and thrown an error to the terminal if it fails (covered by acceptance test)
     *
     * @param $renderedOutput string
     *
     * @return bool
     */
    private function writeTemplate($renderedOutput, $formPath)
    {
        try {
            file_put_contents($formPath, $renderedOutput);
        }

        catch(\Exception $e) {

            // return false instead of throwing a custom exception as I want to return a custom console error to user
            return false;
        }

	    return true;
    }


    /**
     * Get and return the instance of Mustache
     *
     * @return object
     */
    public function getMustache()
    {
        return $this->mustache;
    }


    /**
     * Initialise the instance of Mustache that we shall be using
     */
    private function setMustache()
    {
        $this->mustache = new Mustache_Engine;
    }


    /**
     * Get the contents of the file template
     *
     * @return string
     */
    public function getTemplateContents()
    {
        return file_get_contents(__DIR__ . '/../Templates/GenerateFormTemplate.stub');
    }
}
