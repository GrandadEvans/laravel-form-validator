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
     * The result string to return
     *
     * @var string  $ret
     */
    private $returnResult = 'pass';

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
     * @param Mustache_Engine      $mustache
     * @param Filesystem           $filesystem
     * @param array                $details
     * @param string               $formPath
     *
     * @return  array               Returns an array of results including status and path
     */
    public function build(Mustache_Engine $mustache, Filesystem $filesystem, $details, $formPath)
    {
        $this->formPath = $formPath;

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
     * @param       $mustache
     * @param array $args
     * @param       $filesystem
     *
     * @internal param array $args
     *
     * @return  string
     */
    public function renderTemplate($mustache, $args, $filesystem)
    {
        $contents = $this->getTemplateContents($filesystem);
        return $mustache->render($contents, $args);
    }


    /**
     * Try to write the rendered output and thrown an error to the terminal if it fails (covered by acceptance test)
     *
     * @param $filesystem
     * @param $renderedOutput string
     * @param $formPath
     *
     * @internal param $command
     * @return bool
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
	    return [
		    'status' => 'success',
	        'path' => $formPath
	    ];
    }


    /**
     * Get the contents of the file template
     *
     * @return string
     */
    public function getTemplateContents($filesystem)
    {
        $path = __DIR__ . '/../Templates/GenerateFormTemplate.stub';

        return $filesystem->get($path);
    }
}
;;
