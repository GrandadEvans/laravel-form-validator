<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleBuilderSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith("baz:required:between(3,6):digits | qux:between(3,6)");
    }

    function it_splits_individual_rules()
    {
        $this->separateIndividualRules()->shouldHaveCount(2);
    }

	function it_should_pass_if_a_correct_condition_is_set()
	{
		$this->checkConditionExists('digits');
	}

    public function it_returns_an_array_of_reformatted_rules()
    {
        $this->getReformattedRules()->shouldBeArray();
    }


    public function it_sets_and_gets_the_rules_array_correctly()
    {
        $this->getRulesArray()->shouldHaveCount(2);
    }


    function it_should_throw_an_error_if_a_nonexistent_condition_is_set()
	{
		$this->shouldThrow("\Exception")->during('checkConditionExists', ['nonexistentCondition']);
	}

    function it_splits_the_passed_rules_string_into_individual_rules()
    {
        $this->separateIndividualRules('1|2')->shouldHaveCount(2);
    }
}
