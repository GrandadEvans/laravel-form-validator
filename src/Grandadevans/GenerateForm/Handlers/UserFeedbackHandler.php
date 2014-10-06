<?php

namespace Grandadevans\GenerateForm\Handlers;

/**
 * Class UserFeedbackHandler
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class UserFeedbackHandler
{

    /**
     * The instance of the command is needed to pass information back to the user
     *
     * @var Command $command    Instance of the Command
     */
    public $command;


    /**
     * The main handler for the class which iterates through the feedback status and farms the logic out
     *
     * @param Command   $command    The passed instance of the Command
     * @param array     $details    The feedback details
     */
    public function provideFeedback($command, $details)
    {
        $this->command = $command;

        switch ($details['status']) {
            case 'success':
                $this->showUserCommandSuccessful($details['path']);
                break;

            case 'exists':
                $this->doesTheUserWantToOverwriteExistingFile($details['path']);
                break;

            case 'fail':
                $this->showUserCommandHasFailed($details['path']);
                break;
        }
    }


	/**
     * Tell the user that there has been an error and display it in bright red letters!
     *
	 * @param string    $path   The path that has failed
	 */
	public function showUserCommandHasFailed($path)
	{
		$this->command->error('The form could not be saved to:');
		$this->command->error($path);
	}


	/**
     * Tell the user that they should thank their lucky stars that another developer has tested their open source code ;-)
     *
	 * @param string    $path   The path that has been created
	 */
	public function showUserCommandSuccessful($path)
	{
		$this->command->info('Form has been saved to');
		$this->command->info($path);
	}


	/**
     * Ask the user is they wish to overwrite an existing file
     *
	 * @param string    $path   The path that already exists
	 */
	public function doesTheUserWantToOverwriteExistingFile($path)
	{
		if (false !== $this->command->confirm(
			"This form validator at:\n" . $path . "\nalready exists: Do you want to overwrite it? (y|N)",
			false)) {

			// The user wants to overwrite the file so fire the process again with the force option set to true
			$this->command->fire(true);
			exit;

		}

		$this->command->error('You have chosen NOT to overwrite the file...Good choice!');
		exit;
	}
}
