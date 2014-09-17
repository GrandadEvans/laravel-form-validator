<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use grandadevans\Helpers;

class FormGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:form';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new validation form';

    protected $formPath;

    protected $formContents;

    protected $namespace;

    protected $className;

    protected $rulesString;

    protected $processedRules;


	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        define('DS', DIRECTORY_SEPARATOR);

        parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->setClassName();

        $this->setNamespace();

        $this->setFormPath();

        $this->setRulesString();

        $this->buildRules();

        $buildResult = $this->buildOutput();

		$this->provideFeedback($buildResult);
	}

    protected function buildRules()
    {
        $ruleBuilder = new grandadevans\RuleBuilder($this->getRulesString());
        $this->processedRules = $ruleBuilder->getReformattedRules();
    }

    protected function buildOutput()
    {
        return new grandadevans\OutputBuilder(
            $this->processedRules,
            $this->getClassName(),
            $this->getNamespace(),
            $this->getFormPath()
        );

    }


	protected function provideFeedback($result)
	{
		if ($result) {
			$this->info("Form Generated!");
			$this->info("The Form has been written to \"" . $this->getFormPath() . "\"");
			return;
		}

		$this->error("The Form could not be written to \"" . $this->getFormPath() . "\"");
			return;

	}

    protected function setRulesString()
    {
        $this->rulesString = $this->option('rules');
    }
    protected function getRulesString()
    {
        return $this->rulesString;
    }


    protected function setNamespace()
    {
        $namespace = "";
        $option = $this->option('namespace');

        if (isset($option)) {
            $namespace = $this->option('namespace');
        }
       $this->namespace = $namespace;
    }
    protected function getNamespace()
    {
        return $this->namespace;
    }


    protected function setFormPath()
    {
        $option = $this->option('dir');
        $name = $this->argument('name');

        $path = base_path() . DS . $option . DS . $name . "Form.php";

        $convertNamespaceToPath = Helpers::convertNamespaceToPath($path);
        $sanitizePath   = Helpers::sanitizePath($convertNamespaceToPath);

        $this->formPath = $sanitizePath;
    }
    protected function getFormPath()
    {
        return $this->formPath;
    }


    protected function setClassName()
    {
        $this->className = $this->argument('name');
    }
    protected function getClassName()
    {
        return $this->className;
    }


	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the form to generate.'),
		);
	}
	protected function getOptions()
	{
		return array(
			array('dir', null, InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'),
			array('namespace', null, InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null),
			array('rules', null, InputOption::VALUE_OPTIONAL, 'The rules of the generated form. Separate the rules with a pipe | as commas are used in rules such as between(3,6)', null),
		);
	}

}
