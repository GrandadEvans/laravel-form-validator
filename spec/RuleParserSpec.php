<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RuleBuilder');
    }
}
