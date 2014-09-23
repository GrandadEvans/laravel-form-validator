<?php namespace Grandadevans\GenerateForm\FormGenerator;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\Handlers\AttributeHandler;
use Grandadevans\GenerateForm\Handlers\PathHandler;
use Grandadevans\GenerateForm\HelperClasses\Helpers;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FormGenerator
 */
class FormGenerator {

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
     * @var PathHandler
     */
    private $pathHandler;
    /**
     * @var RuleBuilder
     */
    private $ruleBuilder;
    /**
     * @var AttributeHandler
     */
    private $attributeHandler;

	/**
	 * Form Details
	 */
	private $details;


	/**
	 * Create a new command instance.
	 *
	 * @param AttributeHandler $attributeHandler
	 * @param PathHandler      $pathHandler
	 * @param RuleBuilder      $ruleBuilder
	 * @param array            $details
	 *
	 * @return bool
	 */
    public function generate(
	    AttributeHandler $attributeHandler,
	    PathHandler $pathHandler,
	    RuleBuilder $ruleBuilder,
	    array $details
    )
    {
	    $this->attributeHandler = $attributeHandler;
	    $this->pathHandler = $pathHandler;
	    $this->ruleBuilder = $ruleBuilder;
	    $this->details = $details;

	    return $details;
    }

    public function setDependancies(PathHandler $pathHandler, RuleBuilder $ruleBuilder, AttributeHandler $attributeHandler)
    {

        $this->pathHandler = $pathHandler;
        $this->ruleBuilder = $ruleBuilder;
        $this->attributeHandler = $attributeHandler;

        parent::__construct();
    }


	/**
	 *
	 */
	public function fire()
    {
	    $this->setDependancies();
        $this->setFormAttributes();
        $buildResult = $this->attemptToBuildForm();

        $this->provideFeedback($buildResult);
    }


	/**
	 * Set all of the form attributes to disk or persist in another way
	 *
	 * @todo test?
	 *
	 * @param $details
	 */
    private function setFormAttributes($details)
    {
        new $this->attributeHandler([
                                        'className' => $details->argument('name'),
                                        'dir' => $details->option('dir'),
                                        'rules' => $details->option('rules'),
                                        'namespace' => $details->option('namespace')
                                    ]);
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
        $ruleBuilder = $this->instantiateRuleBuilder($this->rulesString);

        return $this->buildOutput($ruleBuilder->getReformattedRules());
    }


    /**
     * Process the rules (Method Tested by PHPSpec)
     *
     * @param $rulesString
     *
     * @return mixed
     */
    public function instantiateRuleBuilder($rulesString)
    {
        $rb = new $this->ruleBuilder;
        var_dump($rb);
        $rb->buildRules($rulesString);
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
     * @return mixed
     */
    public function pathHandler()
    {
        return new $this->pathHandler($this);
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
