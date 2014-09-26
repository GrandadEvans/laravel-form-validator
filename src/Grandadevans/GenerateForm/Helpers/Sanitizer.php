<?php

namespace Grandadevans\GenerateForm\Helpers;


class Sanitizer {


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
}
