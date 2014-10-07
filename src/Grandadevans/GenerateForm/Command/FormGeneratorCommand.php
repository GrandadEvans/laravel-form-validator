<?php namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\FormGenerator\FormGenerator;
use Grandadevans\GenerateForm\Handlers\PathHandler;
use Grandadevans\GenerateForm\Handlers\UserFeedbackHandler;
use Grandadevans\GenerateForm\Helpers\Sanitizer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * The Console Command for Grandadevans\laravel-form-validator
 *
 * Class    FormGeneratorCommand
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class FormGeneratorCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string  $name   The name of the command as it will appear under "php artisan"
     */
    protected $name = 'generate:form';

    /**
     * The console command description.
     *
     * @var string  $description    The description of the command when "php artisan help generate:form" is used
     */
    protected $description = 'Generate a new validation form';

    /**
     * Reference for the injected FormGenerator object
     *
     * @var FormGenerator   $formGenerator  The FormGenerator Class that is injected into the constructor
     */
    private $formGenerator;

    /**
     * References the injected UserFeedbackHandler
     *
     * @var UserFeedbackHandler $userFeedbackHandler    The UserFeedbackHandler class injected into the constructor
     */
    private $userFeedbackHandler;


    /**
     * Create a new command instance.
     *
     * @param FormGenerator       $formGenerator
     * @param UserFeedbackHandler $userFeedbackHandler
     */
    public function __construct(FormGenerator $formGenerator, UserFeedbackHandler $userFeedbackHandler)
    {

        parent::__construct();

        $this->formGenerator       = $formGenerator;
        $this->userFeedbackHandler = $userFeedbackHandler;
    }


    /**
     * Execute the console command.
     *
     * @param   bool    $force  Whether any existing file should be overwritten or not
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

        $result       = $this->createFormThroughDedicatedClass($details);

        $showFeedback = $this->userFeedbackHandler->provideFeedback($this, $result);

        if (false === $showFeedback) {
            $this->error("There was an unexpected error");
        }
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
     * Create form through class whose sole responsibility is to manage the form
     *
     * Create the form by passing the responsibility to it's own dedicated class
     * and inject the instances we need as well as passing through the details
     *
     * @param   array $details  An array of all the command's details
     *
     * @return  array
     */
    private function createFormThroughDedicatedClass($details)
    {
        $results = $this->formGenerator->generate(
            new RuleBuilder,
            new PathHandler,
            new OutputBuilder,
            new Filesystem,
            new Sanitizer,
            $this,
            $details
        );

        return $results;
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
     * An array of options specified by the user: format is [
     *      name-of-option,
     *      shortcut-key,
     *      (whether required or optional),
     *      description-of-the-option,
     *      default-value
     * ]
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['dir',       'd', InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'],
            ['namespace', 's', InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null],
            ['rules',     'r', InputOption::VALUE_OPTIONAL,
                'The rules of the generated form eg --rules="username|required|min:6 & alpha|password|required|alpha"',
                null],
        ];
    }
}
