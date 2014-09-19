<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Exception;

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
     * @var
     */
    public $rulesArray;

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
        $this->individualRules = $this->separateIndividualRules($rules);
        $this->processIndividualRules();
    }

    public function getReformattedRules()
    {
        return $this->rulesArray;
    }

    /**
     * @var string  $rules
     *
     * @return array
     */
    public function separateIndividualRules($rules = null)
    {
        if ( ! is_null($rules)) {
            return preg_split("/ ?\| ?/", $rules);
        }
    }

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
     * @throws Exception
     */
    public function separateNextRuleIntoComponentRules($rule)
    {
        $laravelConditions = preg_split("/ ?: ?/", $rule); // ['required', 'min']
        $inputName = $laravelConditions[0];
        $validInputConditions = $this->extractLaravelConditionsFromRule($laravelConditions);
        $this->AddRuleToMainRulesArray($validInputConditions, $inputName);
        $this->shortenRuleStackByOne();
    }

    /**
     * @param $unsanitisedCondition
     *
     * @throws Exception
     */
    public  function checkConditionExists($unsanitisedCondition)
    {
	    $colonLess = explode(':', $unsanitisedCondition);
	    $bracketLess = explode('(', $colonLess[0]);
        // I'm not interested in the stuff in brackets or after colons so separate them all out

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
    protected function AddRuleToMainRulesArray($inputConditions, $inputName)
    {
        if (!empty($inputConditions)) {
            $this->rulesArray[] = [
                'name' => "{$inputName}",
                'conditions' => $inputConditions
            ];
        }
    }

    /**
     *Shorten the rule stack by one
     */
    public function shortenRuleStackByOne()
    {
        array_shift($this->individualRules);
    }

    /**
	 * @return array
	 */
	public function getIndividualRules()
	{
		return $this->individualRules;
	}


     /**
	 * @return array
	 */
	public function getRulesArray()
	{

		return $this->rulesArray;
	}


    /**
     * @param $laravelConditions
     *
     * @return array
     */
    protected function extractLaravelConditionsFromRule($laravelConditions)
    {
        $validInputConditions = "";

        $i = 1;
        while (isset($laravelConditions[$i])) {
            $this->checkConditionExists($laravelConditions[$i]);

            $validInputConditions .= $laravelConditions[$i] . '|';

            $i++;
        }

        $validInputConditions = trim($validInputConditions, '|');

        return $validInputConditions;
    }
}
