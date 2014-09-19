<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Grandadevans\GenerateForm\BuilderClasses\RuleBuilder');
    }
}
