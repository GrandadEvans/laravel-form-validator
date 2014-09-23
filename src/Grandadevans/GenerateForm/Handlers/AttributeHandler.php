<?php namespace Grandadevans\GenerateForm\Handlers;


class AttributeHandler
{

	private $command;

    public $className;

    public $formDir;

    public $rulesString;

    public $namespace;

	public function setAttributes($attributes)
	{
        foreach($attributes as $key => $value) {
            $this->$$key = $value;
        }
        dd($this);
	}

    public function get($name)
    {
        return $this->{$name};
    }

} 
