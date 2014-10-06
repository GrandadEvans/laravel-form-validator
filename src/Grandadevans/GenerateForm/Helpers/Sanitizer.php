<?php

namespace Grandadevans\GenerateForm\Helpers;

use \Exception;

/**
 * The Output Builder for Grandadevans\laravel-form-validator
 *
 * Class    Sanitizer
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class Sanitizer
{

	/**
	 * An array of conditions allowed by the Laravel framework
	 *
	 * @var array $allowableConditions      An array of laravel allowed conditions
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
	 * Convert Namespaces to paths
	 *
	 * @param string    $path   The namespace to sanitize
	 *
	 * @return string           The sanitized namespace converted to path
	 */
	public function convertNamespaceToPath($path)
	{
		return str_replace('\\', DS, $path);
	}


	/**
	 * Strip any extra directory separators from any paths such as home//john
	 *
     * @param  string   $in     The path to be sanitized
	 *
	 * @return string           The returned path free of double directory separators
	 */
	public function stripDoubleDirectorySeparators($in)
	{
		while(strstr($in, DS.DS)) {
			$in = str_replace(DS.DS, DS, $in);
		}

		return $in;
	}


	/**
	 * Here we make the valid conditions back into a string for the validation class
	 *
	 * @param   array   $laravelConditions      An array of conditions to extract and validate
	 *
	 * @return  string                          A string of conditions for this specific rule
	 */
	public function extractLaravelConditionsFromRule($laravelConditions)
	{
		$validInputConditions = "";

        /*
         * Use while and not a foreach as we need to skip over the first index as that is the name of the field
         */
        $i = 1;
		while (isset($laravelConditions[$i])) {

            $validInputConditions = $this->extractConditionIfValid($laravelConditions, $i, $validInputConditions);

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
     * @param $laravelConditions
     * @param $i
     * @param $validInputConditions
     *
     * @return string
     * @throws Exception
     */
    private function extractConditionIfValid($laravelConditions, $i, $validInputConditions)
    {
        $this->checkConditionExists($laravelConditions[$i]);

        // Separate each valid condition with a pipe
        $validInputConditions .= $laravelConditions[$i] . '|';

        return $validInputConditions;
    }
}
