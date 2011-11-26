<?php

/**
 * PhpDirectory
 * @link git@github.com:masicek/PhpDirectory.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpDirectory;

require_once __DIR__ . '/Directory.php';

use PhpDirectory\Directory;

/**
 * Short variant for class Directory
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class D
{


	/**
	 * Check if the directory exists
	 *
	 * @param string $directory
	 *
	 * @throws Exception Directory not exists
	 * @return string Input directory
	 */
	static public function c($directory)
	{
		return Directory::check($directory);
	}


	/**
	 * Make path from list of arguments.
	 *
	 * @return string
	 */
	static public function m()
	{
		return call_user_func_array(__NAMESPACE__ . '\Directory::make', func_get_args());
	}


	/**
	 * Make path from list of arguments and check if the directory exists.
	 *
	 * @return string
	 */
	static public function mc()
	{
		return call_user_func_array(__NAMESPACE__ . '\Directory::makeAndCheck', func_get_args());
	}


}
