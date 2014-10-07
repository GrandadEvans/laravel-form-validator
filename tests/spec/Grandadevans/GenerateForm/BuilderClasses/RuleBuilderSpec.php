<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery as m;
use Grandadevans\GenerateForm\Helpers\Sanitizer;

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
	private $rulesToPass = "baz|required|between:3,6|digits & qux|between:3,6";


	/**
	 * Assign the rules to pass into the construct
	 *
	 * @todo not workin as sengi
	 */
    public function let()
    {
	    $sanitizer = m::mock('Grandadevans\GenerateForm\Helpers\Sanitizer');
	    $sanitizer->shouldReceive('extractLaravelConditionsFromRule')->andReturn('condition1|condition2');

        $this->buildRules($sanitizer, $this->rulesToPass);
    }


	/**
	 * Test separateIndividualRules
	 */
    public function it_splits_individual_rules()
    {
        $this->separateIndividualRules($this->rulesToPass)->shouldHaveCount(2);
    }


	/**
	 * Test separateIndividualRules with space around the ampersand
	 */
    public function it_splits_individual_rules_with_space_around_ampersand()
    {
        $this->separateIndividualRules(str_replace('&', ' & ', $this->rulesToPass))->shouldHaveCount(2);
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
        $this->getCompletedRulesAsArray()->shouldBeArray();
    }



	/**
	 * Test getCompletedRules
	 */
	public function it_sets_and_gets_the_rules_array_correctly()
    {
        $this->getCompletedRules()->shouldHaveCount(2);
    }

}
