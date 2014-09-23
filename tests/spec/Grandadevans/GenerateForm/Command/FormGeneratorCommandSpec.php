<?php

namespace Spec\Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

class FormGeneratorCommandSpec extends ObjectBehavior
{
    function it_should_instantiate_the_rule_builder()
    {
        $rules = 'baz:required:min(6)|bar:required:email';

        $this->buildRules($rules)->shouldBeArray();
        $this->buildRules($rules)->shouldHaveCount(2);
    }

}
