<?php

namespace Spec\Grandadevans\GenerateForm\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormGeneratorCommandSpec extends ObjectBehavior
{
    function it_should_instantiate_the_rule_builder()
    {
        $rules = 'baz:required:min(6)|bar:required:email';

        $this->buildRules($rules)->shouldBeArray();
        $this->buildRules($rules)->shouldHaveCount(2);
    }

}
