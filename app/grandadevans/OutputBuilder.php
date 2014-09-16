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
     * @param array $rules
     * @param string $className
     * @param null  $namespace
     *
     * @internal param string $class
     */
    public function __construct(Array $rules, $className = '', $namespace = null)
    {
//        print_r($rules[0][1]);exit;
        $this->rules = $rules;
        $this->instantiateMustache();
        $this->namespace = $namespace;
        $this->className = ($className) ?: $this->className;

        $this->renderTemplate();
        $this->writeTemplate();
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

        $this->mustache->render($contents, $args);
    }

    protected function writeTemplate()
    {
        $templateContents = file_get_contents('app/stubs/template.stub');

        file_put_contents($this-getDestination(), $templateContents);
    }
}
