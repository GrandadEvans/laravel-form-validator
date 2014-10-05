<?php

namespace spec\Helpers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SanitizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Helpers\Sanitizer');
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
