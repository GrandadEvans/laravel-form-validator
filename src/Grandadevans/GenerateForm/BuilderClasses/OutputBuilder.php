<?php namespace Grandadevans\GenerateForm\BuilderClasses;

use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;
use \ClassPreloader\Command;

/**
 * The Output Builder for Grandadevans\laravel-form-validator
 *
 * Class    OutputBuilder
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class OutputBuilder {

    /**
     * The Final path of the form
     *
     * @var string
     */
    private $formPath;


    /**
     * Accept the params and kick out the output
     *
     * Accept the rules, className, namespaceName and path of the form and write the requested file
     *
     * @param Mustache_Engine      $mustache        Instance of the Mustache_Engine
     * @param Filesystem           $filesystem      Instance of the Illuminate Filesystem class
     * @param array                $details         An array of the required command details
     * @param string               $formPath        The full form path
     *
     * @return  array                               Returns an array of results including status and path
     */
    public function build(Mustache_Engine $mustache, Filesystem $filesystem, $details, $formPath)
    {
        $this->formPath = $formPath;

	    // If the namespace is not set then...well...set it.
	    $namespace = ($details['namespace']) ? : null;

	    $renderedOutput = $this->renderTemplate(
            $mustache,
            [
                'rules'     => $details['rules'],
                'namespace' => $namespace,
                'className' => $details['className']
            ],
            $filesystem
        );

        return $this->writeTemplate($filesystem, $renderedOutput, $formPath);
    }


    /**
     * Render the Template using the instance of Mustache and return the results
     *
     * @param Mustache_Engine   $mustache       Instance of the Mustache Engine
     * @param array             $args           Array of the required command details
     * @param Filesystem        $filesystem     Instance of the Illuminate Filesystem class
     *
     * @return  string                          Return the rendered template
     */
    public function renderTemplate($mustache, $args, $filesystem)
    {
        $contents = $this->getTemplateContents($filesystem);

        return $mustache->render($contents, $args);
    }


    /**
     * Try to write the rendered output and thrown an error to the terminal if it fails
     *
     * @param Filesystem        $filesystem         Instance of the Illuminate Filesystem class
     * @param string            $renderedOutput     The rendered template
     * @param string            $formPath           The full form path
     *
     * @return array                                Return an array of results
     */
    public function writeTemplate($filesystem, $renderedOutput, $formPath)
    {
		if (false === $filesystem->put($formPath, $renderedOutput)) {

			// return false instead of throwing a custom exception as I want to return a custom console error to user
			return [
				'status' => 'fail',
			    'path' => $formPath
			];
		}

	    // If we have reached here then we have success
	    return [
		    'status' => 'success',
	        'path' => $formPath
	    ];
    }


    /**
     * Get the contents of the file template
     *
     * @param  Filesystem   $filesystem     Instance of the Illuminate Filesystem class
     *
     * @return string                       Return the full [un-rendered] template contents
     */
    public function getTemplateContents($filesystem)
    {
        $path = __DIR__ . '/../Templates/GenerateFormTemplate.stub';

        return $filesystem->get($path);
    }
}
