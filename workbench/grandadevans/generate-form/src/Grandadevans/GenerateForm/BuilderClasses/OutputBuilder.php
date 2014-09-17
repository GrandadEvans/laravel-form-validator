<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Mustache_Engine;

/**
 * Class OutputBuilder
 *
 * @package grandadevans
 */
class OutputBuilder {

    /**
     * The instance of Mustache
     *
     * @var object
     */
    private  $mustache;

    /**
     * The name of the namespace
     *
     * @var mixed
     */
    private $namespace;

    /**
     * The class name
     *
     * @var string
     */
    private $className = "Foo";

    /**
     * Holder of the rules if there are any
     *
     * @var mixed
     */
    private $rules;


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
    public function __construct($rules, $className = '', $namespace = null, $formPath)
    {
        $this->rules = $rules;
        $this->setMustache();
        $this->namespace = $namespace;
        $this->className = ($className) ?: $this->className;
        $this->formPath  = $formPath;
        $this->className = $className;

        $renderedOutput = $this->renderTemplate();
        $this->writeTemplate($renderedOutput);
    }

    /**
     * Render the Template using the instance of Mustache and return the results
     *
     * @return string
     */
    private function renderTemplate()
    {
        $contents = file_get_contents(__DIR__ . '/../Templates/GenerateFormTemplate.stub');

        $args = [
            'rules'     => $this->rules,
            'namespace' => $this->namespace,
            'className' => $this->className
        ];

        $renderedOutput = $this->mustache->render($contents, $args);

        return $renderedOutput;
    }

    /**
     * Try to write the rendered output and thrown an error to the terminal if it fails
     *
     * @param $renderedOutput string
     */
    private function writeTemplate($renderedOutput)
    {
        try {
            file_put_contents($this->formPath, $renderedOutput);
        }

        catch(\Exception $e) {
            $this->error("The Form could not be written to \"" . $this->getFormPath() . "\"");
        }
    }

    /**
     * Get and return the instance of Mustache
     *
     * @return object
     */
    private function getMustache()
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
}
