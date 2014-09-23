<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Grandadevans\GenerateForm\Handlers\AttributeHandler;
use Grandadevans\GenerateForm\Handlers\PathHandler;
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
     * @var FormGenerator
     */
    private $formGenerator;
	/**
	 * @var AttributeHandler
	 */
	private $attributeHandler;


	/**
	 * Create a new command instance.
	 *
	 * @todo test?
	 *
	 * @param AttributeHandler $attributeHandler
	 * @param PathHandler      $pathHandler
	 * @param RuleBuilder      $ruleBuilder
	 * @param FormGenerator    $formGenerator
	 */
	public function __construct(
		AttributeHandler $attributeHandler,
		PathHandler $pathHandler,
		RuleBuilder $ruleBuilder,
		FormGenerator $formGenerator) {
        parent::__construct();
        $this->formGenerator = $formGenerator;
		$this->attributeHandler = $attributeHandler;
		$this->pathHandler = $pathHandler;
		$this->ruleBuilder = $ruleBuilder;
		$this->formGenerator = $formGenerator;
	}


	/**
	 * Execute the console command.
	 *
     * @return mixed
     */
	public function fire()
	{
		$details = $this->getCommandDetails();

		$results = $this->formGenerator->generate(
	        $this->attributeHandler,
	        $this->pathHandler,
	        $this->ruleBuilder,
	        $details
        );

		$this->provideFeedback($results);
	}


	/**
	 * Let the user know the result of the form generation
	 *
	 * @param $result
	 */
	protected function provideFeedback($result)
	{
		if (false !== $result) {
			$this->info('Success!');
		} else {
			$this->error('The form could not be generated');
		}
	}


    /**
     * @return array
     */
    protected function getCommandDetails()
    {
        return [
            'name'      => $this->argument('name'),
            'dir'       => $this->option('dir'),
            'rules'     => $this->option('rules'),
            'namespace' => $this->option('namespace')
        ];
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
