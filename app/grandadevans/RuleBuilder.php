<?php

namespace grandadevans;

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
     * @var
     */
    public $completedRules;

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
        $this->rules = $rules;

        $this->individualRules = $this->separateIndividualRules();
    }

    /**
     * @return array
     */
    public function separateIndividualRules()
    {
        return preg_split("/ ?\| ?/", $this->rules);
    }

    /**
     * @throws Exception
     */
    public function separateNextRuleIntoComponentRules()
    {
        $laravelConditions = preg_split("/ ?: ?/", $this->individualRules[0]); // ['required', 'min']

        $inputName = $laravelConditions[0];
        $validInputConditions = [];

        $i=1;

        while(isset($laravelConditions[$i])) {
            $this->checkConditionExists($laravelConditions[$i]);

            $validInputConditions[] = $laravelConditions[$i];

            $i++;
        }

        $this->AddRuleToMainRulesArray($validInputConditions, $inputName);

        $this->shortenRuleStackByOne();
    }


    /*
     * Shorten the rule stack by one
     */
    protected function shortenRuleStackByOne()
    {
        $this->individualRules = array_shift($this->individualRules);
    }

    /**
     * @param $inputConditions
     * @param $inputName
     */
    protected function AddRuleToMainRulesArray($inputConditions, $inputName)
    {
        $this->rulesArray["{$inputName}"] = $inputConditions;
    }

    /**
     * @param $condition
     *
     * @throws Exception
     */
    protected function checkConditionExists($condition)
    {

        // I'm not interested in the stuff in brackets or after colons so separate them all out
        $condition = str_replace("(\[1-9\]", '', $condition);

        if (in_array($condition, $this->allowableConditions)) {
            return true;
        }

        throw new Exception("\"{$condition}\" is not a valid laravel condition");
    }
}
