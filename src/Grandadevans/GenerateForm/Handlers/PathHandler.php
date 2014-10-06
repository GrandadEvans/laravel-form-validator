<?php namespace Grandadevans\GenerateForm\Handlers;

use Grandadevans\GenerateForm\Helpers\Sanitizer;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

/**
 * The Console Command for Grandadevans\laravel-form-validator
 *
 * Class PathHandler
 *
 * @author  john Evans<john@grandadevans.com>
 *
 * @licence https://github.com/GrandadEvans/laravel-form-validator/blob/master/LICENSE LICENSE MIT
 *
 * @package Grandadevans\laravel-form-validator
 */
class PathHandler
{

    /**
     * The reference to the full form path
     *
     * @var string
     */
    private $fullFormPath;


    /**
     * Set the forms full path to a property
     *
     * @param   Sanitizer $sanitizer
     * @param   array     $details
     *
     * @param Filesystem  $filesystem
     *
     * @return  string
     */
	public function getFullPath(Sanitizer $sanitizer, $details, Filesystem $filesystem = null)
	{
		$name = $this->getFileName($details);

		$dir = $this->getDirectory($details, $sanitizer);

		$this->makeSureFinalDirectoryExist($dir, $filesystem);

        $fullPath = $sanitizer->stripDoubleDirectorySeparators($dir . DS . $name);

		$this->fullFormPath = $fullPath;

        return $fullPath;
	}


	/**
     * Get the short file name eg FooForm.php
     *
	 * @param   array $details
     *
     * @return  string
	 */
	public function getFileName($details)
	{
        // Strip "Form" and/or ".php" from the name provided
        $className = preg_replace("/(Form)?(\.php)?/", '', $details['className']);
        
		return $className . "Form.php";
	}


    /**
     * Get the directory to use
     *
     * The --dir option takes priority over everything else;
     * then we convert the --namespace into a PSR-0 directory;
     * finally we use the default directory
     *
     * @param array     $details
     * @param Sanitizer $sanitizer  Instance of Sanitizer
     *
     * @return string
     */
    public function getDirectory($details, $sanitizer)
	{
		if ( ! empty($details['dir'])) {

			$dir = $details['dir'];

		} elseif ( ! empty($details['namespace'])) {
			$dir = $sanitizer->convertNamespaceToPath($details['namespace']);

		} else {

			$dir = 'app/Forms';
		}

		return $dir;
	}


    /**
     * Check to see if the path already exists
     *
     * @param   string      $path
     * @param   Filesystem  $filesystem     Instance of the Illuminate Filesystem
     *
     * @return  bool
     */
    public function doesPathExist($path, $filesystem)
	{
		if (false !== $filesystem->exists($path)) {
			return true;
		}

		return false;
	}


    /**
     * Make sure thar the final directory exists, if not then create it
     *
     * @param string $dir
     * @param mixed     $filesystem     An instance of filesystem may or may not be passed depending on whether testing
     *
     * @return bool
     */
    public function makeSureFinalDirectoryExist($dir, $filesystem = null)
	{
        if (is_null($filesystem)) {
            $filesystem = new Filesystem;
        }

        $isDirectory = $filesystem->isDirectory($dir);

        if ( !$isDirectory) {
            $this->createMissingDirectory($dir, $filesystem);
		}

        return $isDirectory;
	}

    /**
     * @param $dir
     * @param $filesystem
     *
     * @return mixed
     */
    public function createMissingDirectory($dir, $filesystem)
    {
        return $filesystem->makeDirectory($dir, 0755, true);
    }
}
