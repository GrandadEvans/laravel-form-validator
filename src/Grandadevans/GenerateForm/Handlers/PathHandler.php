<?php namespace Grandadevans\GenerateForm\Handlers;

use Grandadevans\GenerateForm\Exceptions\FileExistsException;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class PathHandler
{

    public $fullFormPath;

    public $details;

    public $dir;

    public $name;


    /**
     * Strip any extra directory separators from any paths such as home//john
     *
     * I would consider using this for both namespace replacement and double
     * directory separator replacement
     * 	    while(preg_replace("/(\\\\)|(\.\.)+/", DS, $in) !== $in) {}
	 *
     * @param $in   string
     *
     * @return      string
     */
    public function stripDoubleDirectorySeparators($in)
    {
        while(strstr($in, DS.DS)) {
            $in = str_replace(DS.DS, DS, $in);
        }

        return $in;
    }


	/**
	 * Convert Namespaces to paths
	 *
	 * @param string    $path
	 *
	 * @return string
	 */
	public function convertNamespaceToPath($path)
	{
		return str_replace('\\', DS, $path);
	}


	/**
	 * Set the forms full path to a property
	 */
	public function getFullPath(Filesystem $file, $details)
	{
		$this->file = $file;

		$name = $this->getFileName($details);

		$dir = $this->getDirectory($details);

		$this->makeSureDirectoryExist($dir);

        $fullPath = $this->stripDoubleDirectorySeparators($dir . DS . $name);

		$this->fullFormPath = $fullPath;

        return $fullPath;
	}


	/**
	 * @param $details
	 */
	private function getFileName($details)
	{
		return $details['className'] . "Form.php";
	}


	private function getDirectory($details)
	{
		// If the directory option is set this is to be used
		if ( ! empty($details['dir'])) {

			$dir = $details['dir'];

			// Else if the namespace is set - convert this into it's PSR-0 directory
		} elseif ( ! empty($details['namespace'])) {

			$dir = $this->convertNamespaceToPath($details['namespace']);

			// Finally if nothing has been set use the default
		} else {

			$dir = app_path() . '/Forms';
		}

		return $dir;
	}

	public function doesPathExist($path)
	{
		if (false !== $this->file->exists($path)) {
			return true;
		}

		return false;
	}

	private function makeSureDirectoryExist($dir)
	{
		if ( ! $this->file->isDirectory($dir)) {
			$this->file->makeDirectory($dir, 0755, true);
		}
	}
} 
