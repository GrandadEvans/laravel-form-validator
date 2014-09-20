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
class FormGeneratorCommand extends \Command {

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
	 * The directory of the finished form
	 *
	 * @var string
	 */
	protected $formDir;


	/**
	 * Create a new command instance.
	 *
	 * @todo test?
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
	 * @todo test?
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
     * Set all of the form attributes to disk or persist in another way
     *
     * @todo test?
     */
    private function setFormAttributes()
    {
        $this->className   = $this->argument('name');

        $this->formDir     = $this->option('dir');

        $this->rulesString = $this->option('rules');

        $this->namespace   = $this->option('namespace') ?: "";

        $this->setFullFormPath();
    }


    /**
     * Attempt to build the form
     *
     * @todo test?
     *
     * @return string
     */
    private function attemptToBuildForm()
    {
        $processedRules = $this->buildRules($this->rulesString);

        $buildResult = $this->buildOutput($processedRules);

        return $buildResult;
    }


    /**
     * Process the rules (Method Tested by PHPSpec)
     *
     * @param $rulesString
     *
     * @return mixed
     */
    public function buildRules($rulesString)
	{
		$ruleBuilder          = new RuleBuilder($rulesString);
		return $ruleBuilder->getReformattedRules();
	}


    /**
     * Build the output
     *
     * @param $processedRules
     *
     * @return string
     */
    protected function buildOutput($processedRules)
	{
		$ob = new OutputBuilder(
			$processedRules,
            $this->className,
			$this->namespace,
			$this->getFullFormPath()
		);

		return $ob->returnStatus;

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
		// Set the paths
        $fullPath = "";

        // Check if the base path has been included in the sir option
        if ( ! stristr($this->formDir, base_path()) && ! strstr($this->formDir, '../')) {
            $fullPath = base_path() . DS;
        }

        $fullPath .= $this->formDir . DS . $this->className . "Form.php";

		// Convert and sanitize
		$convertNamespaceToPath = Helpers::convertNamespaceToPath($fullPath);
		$sanitizePath           = Helpers::sanitizePath($convertNamespaceToPath);

		// Set the object properties
		$this->fullFormPath = $sanitizePath;
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
			             "Please make sure the \n\n" . $this->formDir . "\n\ndirectory actually exists!\n\n");
		}
	}


    /**
     * Get a full list of the user provided arguments
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the form to generate.'],
        ];
    }


    /**
     * Get a full list of the user provided options
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['dir', null, InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'],
            ['namespace', null, InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null],
            ['rules', null, InputOption::VALUE_OPTIONAL, 'The rules of the generated form. Separate the rules with a pipe | as commas are used in rules such as between(3,6)', null],
        ];
    }


}
