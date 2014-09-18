<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\HelperClasses\Helpers;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
	 * The full path of the finished form
	 *
	 * @var string
	 */
	protected $fullFormPath;

	/**
	 * The full string contents of the finished form
	 *
	 * @var string
	 */
	protected $formContents;

	/**
	 * The namespace as specified by the user
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * The classname as specified by the user (or defaults to Form)
	 *
	 * @var string
	 */
	protected $className;

	/**
	 * The full rules string as specified by the user
	 *
	 * @var string
	 */
	protected $rulesString;

	/**
	 * The processed rules after they have been validated as actually being correct Laravel conditions
	 *
	 * @var mixed
	 */
	protected $processedRules;

	/**
	 * The directory of the finished form
	 *
	 * @var string
	 */
	protected $formDir;


	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		// Define the directory separator for the OS used
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
		$this->setFormAttributes();

		$buildResult = $this->attemptToBuildForm();

		$this->provideFeedback($buildResult);
	}

	/**
	 * Get the formatted rules
	 */
	protected function buildRules()
	{
		$ruleBuilder          = new RuleBuilder($this->getRulesString());
		$this->processedRules = $ruleBuilder->getReformattedRules();
	}

	/**
	 * @return string
	 */
	protected function getRulesString()
	{
		return $this->rulesString;
	}

	/**
	 * Set the rules string to a property
	 */
	protected function setRulesString()
	{
		$this->rulesString = $this->option('rules');
	}

	/**
	 * Build the form output and persist (write to disk etc)
	 *
	 * @return string
	 */
	protected function buildOutput()
	{
		$ob = new OutputBuilder(
			$this->processedRules,
			$this->getClassName(),
			$this->getNamespace(),
			$this->getFullFormPath()
		);

		return $ob->returnStatus;

	}

	/**
	 * Get the classname from the property
	 *
	 * @return string
	 */
	protected function getClassName()
	{
		return $this->className;
	}

	/**
	 * Set the classname
	 */
	protected function setClassName()
	{
		$this->className = $this->argument('name');
	}

	/**
	 * Get the namespace string
	 *
	 * @return string
	 */
	protected function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * Set the namespace in a property
	 */
	protected function setNamespace()
	{
		$this->namespace = $this->option('namespace') ?: "";
	}

	/**
	 * Get the full form path
	 *
	 * @return string
	 */
	public function getFullFormPath()
	{
		return $this->fullFormPath;
	}

	/**
	 * Set the forms full path to a property
	 */
	protected function setFullFormPath()
	{
		// Get the details
		$dir = $this->option('dir');
		$name   = $this->argument('name');

		// Set the paths
		$formDir = base_path() . DS . $dir;
		$fullPath    = $formDir . DS . $name . "Form.php";

		// Convert and sanitize
		$convertNamespaceToPath = Helpers::convertNamespaceToPath($fullPath);
		$sanitizePath           = Helpers::sanitizePath($convertNamespaceToPath);

		// Set the object properties
		$this->formDir  = $formDir;
		$this->fullFormPath = $sanitizePath;
	}

	/**
	 * Get the directory of the form
	 *
	 * @return string
	 */
	public function getFormDir()
	{
		return $this->formDir;
	}

	/**
	 * Set the directory of the form to a property
	 *
	 * @param string $formDir
	 */
	public function setFormDir($formDir)
	{
		$this->formDir = $formDir;
	}

	/**
	 * Provide feedback to the user either way using the Laravel command->info method
	 *
	 * @param string    $result
	 */
	protected function provideFeedback($result)
	{
		if ($result === 'pass') {
			$this->info("Form Generated!");
			$this->info("The Form has been written to \"" . $this->getFullFormPath() . "\"");
		} else {
			$this->error("The Form could not be written to \"" .
			             $this->getFullFormPath() . "\"\n\n" .
			             "Please make sure the \n\n" . $this->getFormDir() . "\n\ndirectory actually exists!\n\n");
		}
	}

	/**
	 * Get a full list of the user provided arguments
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the form to generate.'),
		);
	}

	/**
	 * Get a full list of the user provided options
	 *
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

	/**
	 * Set all of the form attributes to disk or persist in another way
	 */
	private function setFormAttributes()
	{
		$this->setClassName();

		$this->setNamespace();

		$this->setFullFormPath();

		$this->setRulesString();
	}

	/**
	 * Attempt to build the form
	 *
	 * @return string
	 */
	private function attemptToBuildForm()
	{
		$this->buildRules();

		$buildResult = $this->buildOutput();

		return $buildResult;
	}

}
