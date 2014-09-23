<?php

namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

/**
 * Class FormGeneratorCommandTest
 *
 * @package Grandadevans\GenerateForm\Command
 */
class FormGeneratorCommandTest extends PHPUnit_Framework_TestCase
{

	public function __construct()
	{

	}

	public function tearDown()
	{
		m::close();
	}

    /**
     * Test to make sure that if the FormGenerator class returns true the correct feedback is provided
     */
    public function testTheConsoleCommandReturnsOk()
    {
        // Arrange
        $formGenerator = m::mock('Grandadevans\GenerateForm\FormGenerator\FormGenerator');
	    $formGenerator->shouldReceive('generate')->andReturn(true);

	    $attributeHandler = m::mock('Grandadevans\GenerateForm\Handlers\AttributeHandler');

	    $pathHandler = m::mock('Grandadevans\GenerateForm\Handlers\PathHandler');

	    $ruleBuilder = m::mock('Grandadevans\GenerateForm\BuilderClasses\RuleBuilder');

	    // Act
	    $tester = new CommandTester(new FormGeneratorCommand(
		                                $attributeHandler,
		                                $pathHandler,
		                                $ruleBuilder,
		                                $formGenerator
	                                ));
        $tester->execute(['name' => 'Foo']);

        // Assert
        $this->assertEquals(trim($tester->getDisplay()), 'Success!');
    }

    /**
     * Test to make sure that if the FormGenerator class returns false the correct feedback is provided
     */
    public function testTheConsoleCommandReturnswithError()
    {
	    // Arrange
	    $formGenerator = m::mock('Grandadevans\GenerateForm\FormGenerator\FormGenerator');
	    $formGenerator->shouldReceive('generate')->andReturn(false);

	    $attributeHandler = m::mock('Grandadevans\GenerateForm\Handlers\AttributeHandler');

	    $pathHandler = m::mock('Grandadevans\GenerateForm\Handlers\PathHandler');

	    $ruleBuilder = m::mock('Grandadevans\GenerateForm\BuilderClasses\RuleBuilder');

	    // Act
	    $tester = new CommandTester(new FormGeneratorCommand(
		                                $attributeHandler,
		                                $pathHandler,
		                                $ruleBuilder,
		                                $formGenerator
	                                ));
	    $tester->execute(['name' => 'Foo']);

	    // Assert
        $this->assertEquals(trim($tester->getDisplay()), 'The form could not be generated');
    }
}
