<?php

namespace spec\grandadevans;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('grandadevans\RuleBuilder');
    }
}
