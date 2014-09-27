<?php

namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\Command\FormGeneratorCommand;
use Grandadevans\GenerateForm\Handlers\PathHandler;
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

	    // Act
	    $tester = new CommandTester(new FormGeneratorCommand($formGenerator));
        $tester->execute(['name' => 'Foo']);

        // Assert
        $this->assertContains('Form has been saved to', trim($tester->getDisplay()));
    }

    /**
     * Test to make sure that if the FormGenerator class returns false the correct feedback is provided
     */
    public function testTheConsoleCommandReturnsWithError()
    {
        // Arrange
        $formGenerator = m::mock('Grandadevans\GenerateForm\FormGenerator\FormGenerator');
        $formGenerator->shouldReceive('generate')->andReturn(true);

        // Act
        $tester = new CommandTester(new FormGeneratorCommand($formGenerator));
        $tester->execute(['name' => 'Foo']);

        // Assert
        $this->assertContains('Form has been saved to', trim($tester->getDisplay()));
    }
}
