<?php namespace Grandadevans\GenerateForm\BuilderClasses;




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
     * Main method in charge of building up the rules
     *
     * @param   string  $rules
     *
     * @return  array
     */
    public function buildRules(Sanitizer $sanitizer, $rules)
	{
		$this->sanitizer = $sanitizer;

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
        $validInputConditions = $this->sanitizer->extractLaravelConditionsFromRule($laravelConditions);

	    // ...it is so add it to the list of conditions
        $this->addRuleToMainCompletedRules($validInputConditions, $inputName);

	    // Take that rule off of the individualRules array
        array_shift($this->individualRules);
    }


    /**
     * Add the new condition to the list of rules to be added to the file
     *
     * @param   string  $inputConditions
     * @param   string  $inputName
     */
    private function addRuleToMainCompletedRules($inputConditions, $inputName)
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
}
