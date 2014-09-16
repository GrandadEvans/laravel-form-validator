<?php

namespace spec\grandadevans;

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
       'grandadevans');
   }
//
//    function it_instantiates_a_new_instance_of_mustache()
//    {
//        $this->getMustache()->shouldHaveType('Mustache_Engine');
//    }

    function it_should_output_a_form()
    {
        $this->renderTemplate()->shouldEqual('');
    }
}
