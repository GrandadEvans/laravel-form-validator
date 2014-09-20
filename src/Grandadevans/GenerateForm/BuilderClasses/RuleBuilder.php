<?php namespace Grandadevans\GenerateForm\BuilderClasses;


use \Exception;

/**
 * Class RuleBuilder
 *
 * @package grandadevans
 */
class RuleBuilder
{
    /**
     * @var string
     */
    public $rules;

    /**
     * @var array
     */
    public $completedRules;

    /**
     * @var array
     */
    public $individualRules;

    /**
     * @var array
     */
    protected $allowableConditions = [
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
     * @param $rules
     */
    public function __construct($rules)
    {
	    // Break the incoming rules string into seperate rules
        $this->individualRules = $this->separateIndividualRules($rules);

	    // Now process the individual rules
        $this->processIndividualRules();
    }


	/**
	 * Separate the incoming rules string into individual rules string strings and return an array
	 *
	 * @var string  $rules
	 *
	 * @return array
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
	public function processIndividualRules()
    {
	    $count = $this->individualRules;
	    if (count($count) > 0) {
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

	    // The first one is the name oof the input field
        $inputName = $laravelConditions[0];

	    // Make sure the condition is valid
        $validInputConditions = $this->extractLaravelConditionsFromRule($laravelConditions);

	    // ...it is so add it to the list of conditions
        $this->AddRuleToMainCompletedRules($validInputConditions, $inputName);

	    // Take that rule off of the individualRules array
        array_shift($this->individualRules);
    }

    /**
     * @param $unsanitisedCondition
     *
     * @throws Exception
     */
    public  function checkConditionExists($unsanitisedCondition)
    {
	    // I'm not interested in the stuff in brackets or after colons so separate them all out
	    $colonLess = explode(':', $unsanitisedCondition);
	    $bracketLess = explode('(', $colonLess[0]);
	    $condition = $bracketLess[0];

        if (in_array($condition, $this->allowableConditions)) {
            return true;
        }

        throw new Exception("\"{$condition}\" is not a valid laravel condition");
    }

    /**
     * @param $inputConditions
     * @param $inputName
     */
    protected function AddRuleToMainCompletedRules($inputConditions, $inputName)
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
     * @param $laravelConditions
     *
     * @return array
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
}
