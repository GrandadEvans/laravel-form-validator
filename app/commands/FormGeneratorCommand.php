<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FormGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:form';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new validation form';

    protected $formPath;

    protected $formContents;

    protected $namespace;

    protected $rulesContents;


	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->setNamespace();

        $this->setFormPath();

        $this->buildRules();

        $this->setFormContents();
        $this->saveFormContents();
	}

    protected function buildRules()
    {
        $t = "    ";
        $tx2 = $t . $t;
        $tx3 = $t . $t . $t;

        $rules = $this->option('rules');

        if (empty($rules)) {
            $this->rulesContents = $t . 'protected $rules = array();' . "\n";
            return;
        }

        // split the rules up
        $rules = explode('|', $rules);

        $out = $t . 'protected $rules = array(' . "\n";

        foreach($rules as $rule) {
            $rulePart = explode(':', $rule);

            $ruleName = trim($rulePart[0]);

            $out .= $tx2 . "\"{$ruleName}\" => array(";

            $i = 1;
            while(isset($rulePart[$i])) {
                $part = trim($rulePart[$i]);

                $out .= "\n" . $tx3 . "\"{$part}\",";
                $i++;
            }
            $out .= $tx2 . "\n" . $tx2 . "),\n";
        }
        $out .= $t . ");\n";
        $this->rulesContents = $out;
    }

    protected function getRules()
    {
        return $this->rulesContents;
    }


    protected function setNamespace()
    {
        $namespace = "";
        $option = $this->option('namespace');

        if (isset($option)) {
            $namespace = $this->option('namespace');
        }
       $this->namespace = $namespace;
    }

    protected function getNamespace()
    {
        return $this->namespace;
    }



    protected function setFormContents()
    {
        $lb = PHP_EOL;
        $lbx2 = $lb . $lb;

        $out = "<?php" . $lbx2;

        $name = $this->argument('name');

        $rules = $this->getRules();

        if (!empty($this->namespace)) $out .= "namespace " . $this->namespace . ";". $lbx2;

        $out .= file_get_contents('tests/stubs/FormStub.php');

        // preform a search and replace on the class to set the correct name
        $out = str_replace(
            'class Form extends FormValidator',
            'class ' . $name . 'Form extends FormValidation',
            $out
        );

        // Set the rules
        $out = str_replace('// insert rules here', $rules, $out);

        $this->formContents = $out;
    }

    protected function getFormContents()
    {
        return $this->formContents;
    }

    protected function saveFormContents()
    {
        $contents = $this->getFormContents();
        $path = $this->getFormPath();
        try {
            file_put_contents($path, $contents);
        }

        catch(Exception $e) {

            $this->error('I was unable to save the form: does the "' . base_path() . "/" . $this->option('dir') . '" path exist?');
            exit;
        }

        $this->info('Form Generated!');
    }



    protected function setFormPath()
    {
        $option = $this->option('dir');
        $name = $this->argument('name');

        $path = base_path() . "/{$option}/{$name}Form.php";

        $this->formPath = $path;
    }

    protected function getFormPath()
    {
        return $this->formPath;
    }


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the form to generate.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('dir', null, InputOption::VALUE_OPTIONAL, 'The directory to place the generated form.', 'app/Forms'),
			array('namespace', null, InputOption::VALUE_OPTIONAL, 'The namespace to assign to the generated form.', null),
			array('rules', null, InputOption::VALUE_OPTIONAL, 'The rules of the generated form. Separate the rules with a pipe | as commas are used in rules such as between(3,6)', null),
		);
	}

}
