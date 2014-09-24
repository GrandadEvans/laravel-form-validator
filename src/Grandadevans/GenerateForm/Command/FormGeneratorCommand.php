<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Grandadevans\GenerateForm\Handlers\PathHandler;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
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
	public function fire($force = false)
	{
		$details = $this->getCommandDetails();

		if (false !== $force) {
			$details['force'] = true;
		}
		$results = $this->formGenerator->generate(
			new RuleBuilder,
			new PathHandler,    
			new OutputBuilder,
			new Filesystem,
			$details
		);

		$this->provideFeedback($results);
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
			'namespace' => $this->option('namespace'),
			'force' => false
		];
	}

	/**
	 * Let the user know the result of the form generation
	 *
	 * @param $result
	 */
	protected function provideFeedback($resultDetails)
	{
		if ('fileExists' === $resultDetails['result']) {
			if (false !== $this->confirm("This file already exists: Do you want to overwrite it? (y|N)", false)) {
				$this->fire(true);
				exit;
			} else {
				$this->error('You have chosen NOT to overwrite the file...Good choice!');
				exit;
			}
		}
		if ('fail' !== $resultDetails['result']) {
			$this->info('Form has been saved to
' . $resultDetails['fullFormPath']);
		} else {
			$this->error('The form could not be saved to:
' . $resultDetails['fullFormPath']);
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
