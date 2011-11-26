<?php

/**
 * PhpDirectory
 * @link git@github.com:masicek/PhpDirectory.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpDirectory;

/**
 * Collection of static functions for better work with directory
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Directory
{

	/**
	 * Version of PhpDirectory
	 */
	const VERSION = '0.1.0';


	/**
	 * Check if the directory exists
	 *
	 * @param string $directory
	 *
	 * @throws Exception Directory not exists
	 * @return string Input directory
	 */
	static public function check($directory)
	{
		if (!is_dir($directory))
		{
			throw new \Exception('Directory "' . $directory . '" not exists.');
		}
		return $directory;
	}


	/**
	 * Make path from list of arguments.
	 *
	 * @return string
	 */
	static public function make()
	{
		$pathParts = func_get_args();

		$ds = DIRECTORY_SEPARATOR;
		$path = implode($ds, $pathParts);

		// correct separator
		$path = str_replace('/', $ds, $path);
		$path = str_replace('\\', $ds, $path);

		// replace "/./" and "//"
		$path = str_replace($ds . $ds, $ds, $path);
		$path = str_replace($ds . '.' . $ds, $ds, $path);

		return $path;
	}


	/**
	 * Make path from list of arguments and check if the directory exists
	 *
	 * @return string
	 */
	static public function makeAndCheck()
	{
		$path = call_user_func_array('self::make', func_get_args());
		self::check($path);
		return $path;
	}


}
