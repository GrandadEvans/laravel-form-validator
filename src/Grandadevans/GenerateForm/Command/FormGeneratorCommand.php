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
 * The Console Command for Grandadevans\laravel-form-validator
 *
 * Class FormGeneratorCommand
 *
 * @author  john Evans<john@grandadevans.com>
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 * @package Grandadevans\laravel-form-validator
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
     * Reference for the injected FormGenerator object
     *
     * @var FormGenerator
     */
    private $formGenerator;


    /**
     * Create a new command instance.
     *
     * @param FormGenerator $formGenerator
     */
    public function __construct(FormGenerator $formGenerator)
    {

        parent::__construct();

        $this->formGenerator = $formGenerator;
    }


    /**
     * Execute the console command.
     *
     * @param   bool $force Whether any existing file should be overwritten or not
     *
     * @return  array           Return an array containing result status and form path
     */
    public function fire($force = false)
    {
        $details = $this->getCommandDetails();

        // If $force is set to true then update the $details array
        if (false !== $force) {
            $details['force'] = true;
        }

        $results = $this->createFormThroughDedicatedClass($details);

        $this->provideFeedback($results);
    }


    /**
     * Get the command details including any arguments and options
     *
     * @return array
     */
    private function getCommandDetails()
    {
        return [
            'className'   => $this->argument('name'),
            'dir'         => $this->option('dir'),
            'rulesString' => $this->option('rules'),
            'namespace'   => $this->option('namespace'),
            'force'       => false
        ];
    }


    /**
     * Create the form by passing the responsibility to it's own dedicated class
     * and inject the instances we need as well as passing through the details
     *
     * @param   array $details  An array of all the command's details
     *
     * @return array
     */
    private function createFormThroughDedicatedClass($details)
    {
        $results = $this->formGenerator->generate(
            new RuleBuilder,
            new PathHandler,
            new OutputBuilder,
            new Filesystem,
            $details
        );

        return $results;
    }


    /**
     * Let the user know the result of the form generation
     *
     * @param array $resultDetails  The feedback array contains the status as well as the full form path
     */
    private function provideFeedback($resultDetails)
    {
        if ('fileExists' === $resultDetails['result']) {

            $this->askTheUserIfTheyWishToOverwriteTheExistingFile();

        } elseif ('fail' === $resultDetails['result']) {

            $this->error('The form could not be saved to:');
            $this->error($resultDetails['fullFormPath']);

        } else {

            $this->info('Form has been saved to');
            $this->info($resultDetails['fullFormPath']);

        }
    }


    /**
     * Ask the user if wish to overwrite their path of choice which already exists
     */
    private function askTheUserIfTheyWishToOverwriteTheExistingFile()
    {
        if (false !== $this->confirm("This file already exists: Do you want to overwrite it? (y|N)", false)) {

            // The user wants to overwrite the file so fire the process again with the force option set to true
            $this->fire(true);
            exit;

        } else {

            $this->error('You have chosen NOT to overwrite the file...Good choice!');
            exit;

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
            ['dir', 'd', InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'],
            ['namespace', 's', InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null],
            ['rules', 'r', InputOption::VALUE_OPTIONAL, 'The rules of the generated form. Separate the rules with a pipe | as commas are used in rules such as between(3,6)', null],
        ];
    }
}
