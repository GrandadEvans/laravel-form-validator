<?php

namespace spec\grandadevans;

use grandadevans\RuleBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleBuilderSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith("baz:required:between(3,6):digit | qux:between(3,6)");
    }

    public function it_splits_individual_rules()
    {
        $this->separateIndividualRules()->shouldHaveCount(2);
    }

    public function it_splits_individual_rules_down_into_its_component_rules()
    {
        $this->separateNextRuleIntoComponentRules()->shouldHaveCount(4);
    }
}
