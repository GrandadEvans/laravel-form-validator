<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mustache_Engine;
use Mockery as m;


class OutputBuilderSpec extends ObjectBehavior
{
   function let()
   {
       $mustache = m::mock('Mustache_Engine');
       $mustache->shouldReceive('render')->andReturn('templateContent');

       $filesystem = m::mock('Illuminate\Filesystem\Filesystem');
       $filesystem->shouldReceive('get')->andReturn('templateContent');
       $filesystem->shouldReceive('put')->andReturn('templateContent');


       $this->build(
           $mustache,
           $filesystem,
           [
               'rules' => [],
               'className' => 'FooBar',
               'namespace' => 'grandadevans'
           ],
           'tests/Forms/FooBarForm.php');
   }

    function it_instantiates_a_new_instance_of_mustache()
    {
        $this->getMustache()->shouldHaveType('Mustache_Engine');
    }
}
