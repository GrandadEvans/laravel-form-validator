<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mustache_Engine;


class OutputBuilderSpec extends ObjectBehavior
{
   function let()
   {
       $this->beConstructedWith([

           [
               'name' => 'bar',
               'conditions' => [
                    'unique',
                    'required',
                    'min(5)',
                ]
           ],
            [
                'name' => 'qux',
                'conditions' => [
                    'required',
                    'email',
                ]
            ]
       ],
       'FooBar',
       'grandadevans',
	       'tests/Forms/FooBarForm.php');
   }

    function it_instantiates_a_new_instance_of_mustache()
    {
        $this->getMustache()->shouldHaveType('Mustache_Engine');
    }
}
