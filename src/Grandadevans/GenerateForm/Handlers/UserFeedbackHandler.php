<?php

namespace Grandadevans\GenerateForm\Handlers;

/**
 * Class UserFeedbackHandler
 *
 * @package Grandadevans\GenerateForm\Handlers
 */
class UserFeedbackHandler
{
    
    public $command;
    
    public function provideFeedback($command, $details)
    {
        $this->command = $command;

        switch ($details['status']) {
            case 'success':
                $this->showUserCommandSuccessful($details['path']);
                break;

            case 'exists':
//                return 'too bad';
                $this->doesTheUserWantToOverwriteExistingFile($details['path']);
                break;

            case 'fail':
                $this->showUserCommandHasFailed($details['path']);
                break;
        }
    }
	/**
	 * @param $path
	 */
	public function showUserCommandHasFailed($path)
	{
		$this->command->error('The form could not be saved to:');
		$this->command->error($path);
	}


	/**
	 * @param $path
	 */
	public function showUserCommandSuccessful($path)
	{
		$this->command->info('Form has been saved to');
		$this->command->info($path);
	}


	/**
	 * @param $path
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
