<?php
/**
 * Part of the MyPainChart (MPC) system
 *
 * @author  John Evans<john@grandadevans.com>
 * @package     MPC
 * @copyright   2014 John Evans<john@grandadevans.com>
 */

namespace spec\Grandadevans\GenerateForm\HelperClasses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Support\Facades\Config;

class HelpersSpec extends ObjectBehavior
{

	/**
	 * Test stripDoubleDirectorySeparators
	 */
	public function it_strips_double_directory_separators_from_a_string()
	{
		$this->stripDoubleDirectorySeparators('1//2//3//4')->shouldBeEqualTo('1/2/3/4');
	}


	/**
	 * Test stripExtraParentDirectorySelectors
	 */
	public function it_strips_double_parent_selectors_from_a_string()
	{
		$this->stripExtraParentDirectorySelectors('../../../../')->shouldBeEqualTo('././././');
	}


	/**
	 * Test convertNamespaceToPath
	 */
	public function it_converts_namespaces_to_paths()
	{
		$this->convertNamespaceToPath('\\Grandadevans\\GenerateForm\\HelperClasses\\')->shouldBeEqualTo('/Grandadevans/GenerateForm/HelperClasses/');
	}


	/**
	 * Test sanitizePath
	 */
	public function it_doesnt_sanitize_when_in_debug_config_mode()
	{
//		$this->sanitizePath('\\GrandadEvans/../Grandadevans\\GenerateForm')->shouldBeEquaalTo('/Grandadevans/..//GenerateForm');
	}
}
