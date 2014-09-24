<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
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
	 * Create a new command instance.
	 *
	 * @param FormGenerator    $formGenerator
	 */
	public function __construct(FormGenerator $formGenerator) {
        parent::__construct();
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
            new RuleBuilder,
            new PathHandler,
            new OutputBuilder,
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
		if ('fail' !== $result) {
			$this->info('Form Generated!');
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
            'className'      => $this->argument('name'),
            'dir'       => $this->option('dir'),
            'rulesString'     => $this->option('rules'),
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
