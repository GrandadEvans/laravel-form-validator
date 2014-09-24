<?php namespace Grandadevans\GenerateForm\Handlers;

use Illuminate\Config\Repository as Config;

class PathHandler
{

    public $fullFormPath;

    public $details;

    public $dir;

    public $name;

    /**
     * Strip any extra directory separators from any paths such as home//john
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
	public function getFullPath($details)
	{
		$name = $this->getFileName($details);

		$dir = $this->getDirectory($details);

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

		if ( ! empty($details['dir'])) {
			$dir = $details['dir'];
		} elseif ( ! empty($details['namespace'])) {
			$dir = $this->convertNamespaceToPath($details['namespace']);
		} else {
			$dir = app_path() . '/Forms';
		}

		return $dir;
	}


} 
