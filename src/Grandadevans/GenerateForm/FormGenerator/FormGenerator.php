<?php namespace Grandadevans\GenerateForm\FormGenerator;

use Grandadevans\GenerateForm\BuilderClasses\OutputBuilder;
use Grandadevans\GenerateForm\BuilderClasses\RuleBuilder;
use Grandadevans\GenerateForm\Handlers\PathHandler;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FormGenerator
 */
class FormGenerator {

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
    protected $dir;
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
	 * Form Details
	 */
	private $details;


    /**
     * Create a new command instance.
     *
     * @param RuleBuilder   $ruleBuilder
     * @param PathHandler   $pathHandler
     * @param OutputBuilder $outputBuilder
     * @param array         $details
     *
     * @return array
     */
    public function generate(
        RuleBuilder $ruleBuilder,
        PathHandler $pathHandler,
        OutputBuilder $outputBuilder,
        array $details)
    {
	    $this->pathHandler = $pathHandler;
	    $this->ruleBuilder = $ruleBuilder;
        $this->outputBuilder = $outputBuilder;
	    $this->details = $details;

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
     * @todo test?
     *
     * @return string
     */
    private function attemptToBuildForm()
    {
        $rulesArray = $this->getRulesArrayFromRulesString($this->rulesString);
        return $this->buildOutput($rulesArray);
    }


    /**
     * Process the rules (Method Tested by PHPSpec)
     *
     * @param $rulesString
     *
     * @return mixed
     */
    public function getRulesArrayFromRulesString($rulesString)
    {
        return $this->ruleBuilder->buildRules($rulesString);
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
        $this->outputBuilder->build(
            $processedRules,
            $this->className,
            $this->namespace,
            $this->pathHandler->getFullPath($this->details)
        );

        return $this->outputBuilder->getReturnDetails();

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

    public function getFormPath($details)
    {
        return $this->pathHandler->getFullPath($details);
    }

}
