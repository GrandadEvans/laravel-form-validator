<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;
use \ClassPreloader\Command;


/**
 * The Output Builder for Grandadevans\laravel-form-validator
 *
 * Class OutputBuilder
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
    private $returnResult = 'pass';

	/**
     * The instance of Mustache
     *
     * @var Mustache
     */
    private $mustache;

    /**
     * The Final path of the form
     *
     * @var string
     */
    private $formPath;


	/**
	 * Accept the params and kick out the output
	 *
	 * Accept the rules, className, namespaceName and path of the form and write the requested file
	 *
	 * @param Mustache_Engine   $mustache
	 * @param array             $details
	 * @param string            $formPath
	 */
    public function build(Mustache_Engine $mustache, Filesystem $filesystem, $details, $formPath)
    {
	    $this->mustache = $mustache;

	    $this->filesystem = $filesystem;

        $this->formPath = $formPath;

	    $renderedOutput = $this->renderTemplate([
            'rules'     => $details['rules'],
            'namespace' => $details['namespace'] ?: null,
            'className' => $details['className']
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
            $this->filesystem->put($formPath, $renderedOutput);
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
     * Get the contents of the file template
     *
     * @return string
     */
    private function getTemplateContents()
    {
        return $this->filesystem->get(__DIR__ . '/../Templates/GenerateFormTemplate.stub');
    }
}
