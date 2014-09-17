<?php

namespace grandadevans;

use Mustache_Engine;

/**
 * Class OutputBuilder
 *
 * @package grandadevans
 */
class OutputBuilder
{
    /**
     * @var
     */
    protected $mustache;
    /**
     * @var null
     */
    private $namespace;
    /**
     * @var string
     */
    private $className = "Foo";

    /**
     * @return mixed
     */
    public function getMustache()
    {
        return $this->mustache;
    }

    /**
     * @var array
     */
    private $rules;

	/**
	 * @param array  $rules
	 * @param string $className
	 * @param null   $namespace
	 * @param string $formPath
	 *
	 * @internal param string $class
	 */
    public function __construct($rules, $className = '', $namespace = null, $formPath)
    {
        $this->rules = $rules;
        $this->instantiateMustache();
        $this->namespace = $namespace;
        $this->className = ($className) ?: $this->className;
	    $this->formPath = $formPath;
	    $this->className = $className;

        $renderedOutput = $this->renderTemplate();
        $this->writeTemplate($renderedOutput);
    }

    /**
     *
     */
    protected function instantiateMustache()
    {
        $this->mustache = new Mustache_Engine;
    }

    /**
     *
     */
    public function renderTemplate()
    {
        $contents = file_get_contents('app/stubs/template.stub');

        $args  = [
            'rules' => $this->rules,
            'namespace' => $this->namespace,
            'className' => $this->className
        ];

        $renderedOutput = $this->mustache->render($contents, $args);

	    return $renderedOutput;
    }

    protected function writeTemplate($renderedOutput)
    {
        file_put_contents($this->formPath, $renderedOutput);
    }
}
