<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Mustache_Engine;
use \ClassPreloader\Command;

/**
 * Class OutputBuilder
 *
 * @package grandadevans
 */
class OutputBuilder {

    /**
     * @var string
     */
    public $returnStatus = 'pass';

	/**
     * The instance of Mustache
     *
     * @var object
     */
    private  $mustache;


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
    public function __construct($rules, $className, $namespace = null, $formPath)
    {
        $this->setMustache();

        $renderedOutput = $this->renderTemplate([
            'rules'     => $rules,
            'namespace' => $namespace,
            'className' => $className
        ]);

        if ( ! $this->writeTemplate($renderedOutput, $formPath)) {
	        $this->returnStatus = 'fail';
        }
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

        $renderedOutput = $this->mustache->render($contents, $args);

        return $renderedOutput;
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
     * @return string
     */
    public function getTemplateContents()
    {
        return file_get_contents(__DIR__ . '/../Templates/GenerateFormTemplate.stub');
    }
}
