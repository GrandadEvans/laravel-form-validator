<?php

namespace spec\grandadevans;

use grandadevans\RuleBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleBuilderSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith("baz:required:between(3,6):digits | qux:between(3,6)");
    }

    public function it_splits_individual_rules()
    {
        $this->separateIndividualRules()->shouldHaveCount(2);
    }

	public function it_should_pass_if_a_correct_condition_is_set()
	{
		$this->checkConditionExists('digits');
	}

	public function it_should_throw_an_error_if_a_nonexistent_condition_is_set()
	{
		$this->shouldThrow("\Exception")->during('checkConditionExists', ['nonexistentCondition']);
	}

}
