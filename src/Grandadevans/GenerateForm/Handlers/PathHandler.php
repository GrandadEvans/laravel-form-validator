<?php namespace Grandadevans\GenerateForm\Handlers;

class PathHandler
{

    public $fullPath;


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
	 * Major security flaw in any app. Strip parent dir selectors
	 *
	 * Never allow a user to enter a path and traverse the filesystem by
	 * replacing every instance of a double dot with a single one.
	 *
	 * @param $in   string
	 *
	 * @return      string
	 */
	public function stripExtraParentDirectorySelectors($in)
	{
		while(strstr($in, "..")) {
			$in = str_replace('..', '.', $in);
		}

		return $in;
	}

	/**
	 * Sanitize any public path that gets sent to us
	 *
	 * Only sanitize the path if debug is set to false.
	 * That way when developers are running tests the ../../../ strings they use to go back up the path won't
	 * return an error
	 *
	 * @param $path string
	 *
	 * @return      string
	 */
	public function sanitizePath($path)
	{
		dd('STOP HERE');
		if (false === Config::get('app.debug')) {
			$path = self::stripDoubleDirectorySeparators($path);
			$path = self::stripExtraParentDirectorySelectors($path);
		}

		return $path;
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
	public function setFullPath(array $pathDetails)
	{
		return $this->command->formDir . DS . $this->command->className . "Form.php";

//		$this->fullFormPath = $this->sanitizePath($fullPath);
	}

	/**
	 * Set the forms full path to a property
	 */
	public function getFullPath()
	{
		return $this->fullPath;

//		$this->fullFormPath = $this->sanitizePath($fullPath);
	}


} 
