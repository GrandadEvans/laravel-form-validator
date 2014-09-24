<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class RuleBuilderSpec
 *
 * @package spec\Grandadevans\GenerateForm\BuilderClasses
 */
class RuleBuilderSpec extends ObjectBehavior
{
	/**
	 * @var string
	 */
	private $rulesToPass = "baz:required:between(3,6):digits | qux:between(3,6)";


	/**
	 * Assign the rules to pass into the construct
	 */
    public function let()
    {
        $this->buildRules($this->rulesToPass);
    }


	/**
	 * Test separateIndividualRules
	 */
    public function it_splits_individual_rules()
    {
        $this->separateIndividualRules($this->rulesToPass)->shouldHaveCount(2);
    }


	/**
	 * Test separateIndividualRules
	 */
	public function it_doesnt_try_to_splits_individual_rules_if_nothing_is_passed()
    {
        $this->separateIndividualRules()->shouldBeBool(false);
    }


	/**
	 * Test getReformattedCode
	 */
    public function it_returns_an_array_of_reformatted_rules()
    {
        $this->getReformattedRules()->shouldBeArray();
    }


	/**
	 * Test checkConditionExists
	 */
	public function it_should_pass_if_a_correct_condition_is_set()
	{
		$this->checkConditionExists('digits')->shouldBeBool(true);
	}


	/**
	 * Test checkConditionExists
	 */
	public function it_should_fail_if_an_incorrect_condition_is_set()
	{
		$this->shouldThrow('\Exception')->during('checkConditionExists', ['foobar']);
	}


	/**
	 * Test getCompletedRules
	 */
	public function it_sets_and_gets_the_rules_array_correctly()
    {
        $this->getCompletedRules()->shouldHaveCount(2);
    }


	/**
	 * Test extractLaravelConditionsFromRule
	 */
	public function it_converts_an_array_of_conditions_back_into_a_string()
	{
		$this->extractLaravelConditionsFromRule([
			'password',
			'required',
		    'alpha',
		    'confirmed'
		                                        ])->shouldBeEqualTo('required|alpha|confirmed');

	}
}
