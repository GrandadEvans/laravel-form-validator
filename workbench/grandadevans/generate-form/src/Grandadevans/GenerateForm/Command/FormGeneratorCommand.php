<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\HelperClasses\Helpers;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FormGeneratorCommand
 */
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

	/**
	 * @var
	 */
	protected $formPath;

	/**
	 * @var
	 */
	protected $formContents;

	/**
	 * @var
	 */
	protected $namespace;

	/**
	 * @var
	 */
	protected $className;

	/**
	 * @var
	 */
	protected $rulesString;

	/**
	 * @var
	 */
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

	/**
	 *
	 */
	protected function buildRules()
    {
        $ruleBuilder = new RuleBuilder($this->getRulesString());
        $this->processedRules = $ruleBuilder->getReformattedRules();
    }

	/**
	 * @return mixed
	 */
	protected function getRulesString()
    {
        return $this->rulesString;
    }

	/**
	 *
	 */
	protected function setRulesString()
    {
        $this->rulesString = $this->option('rules');
    }

	/**
	 * @return \grandadevans\OutputBuilder
	 */
	protected function buildOutput()
    {
        return new OutputBuilder(
            $this->processedRules,
            $this->getClassName(),
            $this->getNamespace(),
            $this->getFormPath()
        );

    }

	/**
	 * @return mixed
	 */
	protected function getClassName()
    {
        return $this->className;
    }

	/**
	 *
	 */
	protected function setClassName()
    {
        $this->className = $this->argument('name');
    }

	/**
	 * @return mixed
	 */
	protected function getNamespace()
    {
        return $this->namespace;
    }

	/**
	 *
	 */
	protected function setNamespace()
    {
        $namespace = "";
        $option = $this->option('namespace');

        if (isset($option)) {
            $namespace = $this->option('namespace');
        }
       $this->namespace = $namespace;
    }

	/**
	 * @return mixed
	 */
	protected function getFormPath()
    {
        return $this->formPath;
    }

	/**
	 *
	 */
	protected function setFormPath()
    {
        $option = $this->option('dir');
        $name = $this->argument('name');

        $path = base_path() . DS . $option . DS . $name . "Form.php";

        $convertNamespaceToPath = Helpers::convertNamespaceToPath($path);
        $sanitizePath   = Helpers::sanitizePath($convertNamespaceToPath);

        $this->formPath = $sanitizePath;
    }

	/**
	 * @param $result
	 */
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

	/**
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the form to generate.'),
		);
	}

	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('dir', null, InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'),
			array('namespace', null, InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null),
			array('rules', null, InputOption::VALUE_OPTIONAL, 'The rules of the generated form. Separate the rules with a pipe | as commas are used in rules such as between(3,6)', null),
		);
	}

}
