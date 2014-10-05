<?php namespace Grandadevans\GenerateForm\FormGenerator;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\Handlers\PathHandler;
use Grandadevans\GenerateForm\Helpers\Sanitizer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * The Console Command for Grandadevans\laravel-form-validator
 *
 * Class FormGenerator
 *
 * @author  john Evans<john@grandadevans.com>
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 * @package Grandadevans\laravel-form-validator
 */
class FormGenerator
{

    /**
     * The full path of the finished form
     *
     * @var string
     */
    private $fullFormPath;

    /**
     * The namespace as specified by the user
     *
     * @var string
     */
    private $namespace;

    /**
     * The classname as specified by the user (or defaults to Form)
     *
     * @var string
     */
    private $className;

    /**
     * The full rules string as specified by the user
     *
     * @var string
     */
    private $rulesString;

    /**
     * The directory of the finished form
     *
     * @var string
     */
    private $dir;

    /**
     * Form Details
     */
    private $details;


    /**
     * @var PathHandler
     */
    private $pathHandler;

    /**
     * @var RuleBuilder
     */
    private $ruleBuilder;

    /**
     * @var OutputBuilder
     */
    private $outputBuilder;

	/**
	 * @var Filesystem
	 */
	private $filesystem;


    /**
     * Create a new command instance.
     *
     * @todo I still think this method needs a bit of work as I think it's "ugly"
     *
     * @param RuleBuilder   $ruleBuilder
     * @param PathHandler   $pathHandler
     * @param OutputBuilder $outputBuilder
     * @param Filesystem    $filesystem
     * @param array         $details
     *
     * @return array
     */
    public function generate(
	    RuleBuilder $ruleBuilder,
	    PathHandler $pathHandler,
	    OutputBuilder $outputBuilder,
	    Filesystem $filesystem,
	    Sanitizer $sanitizer,
        FormGeneratorCommand $command,
	    array $details

    ) {
        $this->setDependancies($ruleBuilder, $pathHandler, $outputBuilder, $filesystem, $sanitizer, $command, $details);

	    // Get the full form path
	    $this->fullFormPath = $this->pathHandler->getFullPath($sanitizer, $details);

        // If Force is false and the path exists then return with the error
        if (false === $details['force'] && false !== $this->pathHandler->doesPathExist($this->fullFormPath, $filesystem)) {
            return [
                'path' => $this->fullFormPath,
                'status'       => 'exists'
            ];
        }

        $this->setFormAttributes($details);

        $buildResult = $this->attemptToBuildForm();

        return $buildResult;
    }


    /**
     * Set all of the form attributes to disk or persist in another way
     *
     * @param $details
     */
    private function setFormAttributes($details)
    {
        $this->dir         = $details['dir'];
        $this->className   = $details['className'];
        $this->namespace   = $details['namespace'];
        $this->rulesString = $details['rulesString'];
    }


    /**
     * Attempt to build the form
     *
     * @return string
     */
    private function attemptToBuildForm()
    {
        $rulesArray = $this->getRulesArrayFromRulesString($this->rulesString);

        return $this->buildOutput($rulesArray);
    }


    /**
     * Process the rules
     *
     * @param $rulesString
     *
     * @return mixed
     */
    private function getRulesArrayFromRulesString($rulesString)
    {
        return $this->ruleBuilder->buildRules($this->sanitizer, $rulesString);
    }


    /**
     * Build the output
     *
     * @param $processedRules
     *
     * @return string
     */
    private function buildOutput($processedRules)
    {
        return $this->outputBuilder->build(
	        new Mustache_Engine,
	        new Filesystem,
	        [
                'rules' => $processedRules,
                'className' => $this->className,
                'namespace' => $this->namespace,
	        ],
            $this->pathHandler->getFullPath($this->sanitizer, $this->details)
        );


    }


    /**
     * Set the dependencies
     *
     * @param RuleBuilder   $ruleBuilder
     * @param PathHandler   $pathHandler
     * @param OutputBuilder $outputBuilder
     * @param Filesystem    $filesystem
     * @param array         $details
     */
    private function setDependancies(
	    $ruleBuilder,
	    $pathHandler,
	    $outputBuilder,
	    $filesystem,
	    $sanitizer,
        $command,
	    $details
    ) {
	    $this->pathHandler         = $pathHandler;
        $this->ruleBuilder         = $ruleBuilder;
        $this->outputBuilder       = $outputBuilder;
        $this->file                = $filesystem;
	    $this->sanitizer           = $sanitizer;
        $this->command             = $command;
        $this->details             = $details;
    }
}
