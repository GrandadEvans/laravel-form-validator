<?php namespace Grandadevans\GenerateForm\BuilderClasses;


use \Exception;


/**
 * The Rule Builder for Grandadevans\laravel-form-validator
 *
 * Class RuleBuilder
 *
 * @author  john Evans<john@grandadevans.com>
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 * @package Grandadevans\laravel-form-validator
 */
class RuleBuilder
{
    /**
     * An array of completed rules
     *
     * @var array
     */
    private $completedRules;

    /**
     * An array of individual rules
     *
     * @var array
     */
    private $individualRules;

    /**
     * An array of conditions allowed by the Laravel framework
     *
     * @var array
     */
    private $allowableConditions = [
        'accepted',
        'active_url',
        'after',
        'alpha',
        'alpha_dash',
        'alpha_num',
        'array',
        'before',
        'between',
        'confirmed',
        'date',
        'date_format',
        'different',
        'digits',
        'digits_between',
        'email',
        'exists',
        'image',
        'in',
        'integer',
        'ip',
        'max',
        'mimes',
        'min',
        'not_in',
        'numeric',
        'regex',
        'required',
        'required_if',
        'required_with',
        'required_with_all',
        'required_without',
        'required_without_all',
        'same',
        'size',
        'timezone',
        'unique',
        'url'
    ];


    /**
     * Main method in charge of building up the rules
     *
     * @param   string  $rules
     *
     * @return  array
     */
    public function buildRules($rules)
	{
		$this->individualRules = $this->separateIndividualRules($rules);

		$this->processIndividualRules();

        $arrayOfCompletedRules =  $this->getCompletedRules();

        return $arrayOfCompletedRules;
	}


	/**
	 * Separate the incoming rules string into individual rules strings and return an array
	 *
	 * @param   string  $rules
	 *
	 * @return  array
	 */
	public function separateIndividualRules($rules = null)
	{
		if ( ! is_null($rules)) {
			return preg_split("/ ?\| ?/", $rules);
		}

		return false;
	}


	/**
	 * Called from the Command class to get a list of the properly formatted rules array
	 *
	 * @return mixed
	 */
	public function getReformattedRules()
    {
        return $this->completedRules;
    }


	/**
	 * Process the individual rules eg "foo:required:email" into an array of
	 * individual components ie. "[required,email]"
	 */
	private function processIndividualRules()
    {
	    $count = $this->individualRules;

	    if (false !== $count) {

	        foreach($this->individualRules as $rule) {
		        $this->separateNextRuleIntoComponentRules($rule);
	        }
        }
    }

    /**
     * Split an individual rule down to it's component conditions
     *
     * @param string $rule
     *
     * @throws Exception
     */
    public function separateNextRuleIntoComponentRules($rule)
    {
	    // Separate the conditions
        $laravelConditions = preg_split("/ ?: ?/", $rule); // ['required', 'min']

        $inputName = $laravelConditions[0];

	    // Make sure the condition is valid
        $validInputConditions = $this->extractLaravelConditionsFromRule($laravelConditions);

	    // ...it is so add it to the list of conditions
        $this->AddRuleToMainCompletedRules($validInputConditions, $inputName);

	    // Take that rule off of the individualRules array
        array_shift($this->individualRules);
    }


    /**
     * Accept an unclean condition and make sure it is the array of Laravel allowed conditions
     *
     * @param   string  $unsanitizedCondition
     *
     * @throws  Exception
     */
    public function checkConditionExists($unsanitizedCondition)
    {
        $condition = $this->extractparameterLessCondition($unsanitizedCondition);

        if (in_array($condition, $this->allowableConditions)) {
            return true;
        }

        throw new Exception("\"{$condition}\" is not a valid laravel condition");
    }


    /**
     * Add the new condition to the list of rules to be added to the file
     *
     * @param   string  $inputConditions
     * @param   string  $inputName
     */
    private function AddRuleToMainCompletedRules($inputConditions, $inputName)
    {
        if (!empty($inputConditions)) {

            $this->completedRules[] = [
                'name' => "{$inputName}",
                'conditions' => $inputConditions
            ];
        }
    }


	/**
	 * Return a list of completed rules
	 *
	 * @return array
	 */
	public function getCompletedRules()
	{
		return $this->completedRules;
	}


    /**
     * Here we make the valid conditions back into a string for the validation class
     *
     * @param   array   $laravelConditions
     *
     * @return  array
     */
    public function extractLaravelConditionsFromRule($laravelConditions)
    {
        $validInputConditions = "";

        $i = 1;
        while (isset($laravelConditions[$i])) {

            $this->checkConditionExists($laravelConditions[$i]);

	        // Separate each valid condition with a pipe
            $validInputConditions .= $laravelConditions[$i] . '|';

            $i++;
        }

	    // trim the last pipe from the edges (specifically the right one)
        $validInputConditions = trim($validInputConditions, '|');

        return $validInputConditions;
    }


    /**
     * Accept a string with parameter and strip it down to the raw condition eg between(3,6) --> between
     *
     * @param   string  $unsanitizedCondition
     *
     * @return  string
     */
    private function extractparameterLessCondition($unsanitizedCondition)
    {
        // I'm not interested in the stuff in brackets or after colons so separate them all out
        $colonLess   = explode(':', $unsanitizedCondition);
        $bracketLess = explode('(', $colonLess[0]);
        $condition   = $bracketLess[0];

        return $condition;
    }
}
