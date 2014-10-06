<?php

namespace Grandadevans\GenerateForm\Command;

use Grandadevans\GenerateForm\Handlers\UserFeedbackHandler;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;
use Symfony\Component\Console\Application;


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
	    $formGenerator->shouldReceive('generate')->andReturn([
            'path' => 'app/Forms/FooForm.php',
            'status' => 'success'
        ]);

	    // Act
	    $tester = new CommandTester(new FormGeneratorCommand($formGenerator, new UserFeedbackHandler));
        $tester->execute(['name' => 'Foo'], [
            'verbosity',
            'interactive'
        ]);

        // Assert
        assertContains('Form has been saved to', $tester->getDisplay());
    }

    /**
     * Test to make sure that if the FormGenerator class returns false the correct feedback is provided
     */
    public function testTheConsoleCommandReturnsWithError()
    {
        // Arrange
        $formGenerator = m::mock('Grandadevans\GenerateForm\FormGenerator\FormGenerator');
	    $formGenerator->shouldReceive('generate')->andReturn([
            'path' => 'app/Forms/FooForm.php',
            'status' => 'fail'
        ]);

        // Act
	    $tester = new CommandTester(new FormGeneratorCommand($formGenerator, new UserFeedbackHandler));
        $tester->execute(['name' => 'Foo']);

        // Assert
        assertContains('The form could not be saved to', $tester->getDisplay());
    }

    /**
     * @todo figure out how to mock the getHelper class as it isn't in my system
     *       Then I can mock the user input for the confirm method
     */
}
