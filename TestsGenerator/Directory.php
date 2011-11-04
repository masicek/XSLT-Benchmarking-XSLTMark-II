<?php

namespace XSLTBenchmark\TestsGenerator;


/**
 * Collection static functions for work with directory
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Directory
{


	/**
	 * Check if the directory exists
	 *
	 * @param string $directory
	 *
	 * @throws Exception Directory not exists
	 * @return void
	 */
	static public function check($directory)
	{
		if (!is_dir($directory))
		{
			throw new \Exception('Directory "' . $directory . '" not exists.');
		}
	}


	/**
	 * Make path from input paths. Last path can be path to file.
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


}
