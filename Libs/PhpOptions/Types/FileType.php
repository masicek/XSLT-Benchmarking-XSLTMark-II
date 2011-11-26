<?php

/**
 * PhpOptions
 * @link git@github.com:masicek/PhpOptions.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpOptions;

require_once __DIR__ . '/AType.php';

/**
 * File type
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class FileType extends AType
{

	/**
	 * Base path for check input path
	 *
	 * @var string
	 */
	private $base = NULL;


	/**
	 * Set object
	 * 'base' => base path of input value
	 *
	 * @param array $setting Array of setting of object
	 */
	public function __construct($settings = array())
	{
		parent::__construct($settings);
		if (isset($settings[0]))
		{
			$this->base = $settings[0];
		}
	}


	/**
	 * Check type of value.
	 *
	 * @param mixed $value Checked value
	 *
	 * @return bool
	 */
	public function check($value)
	{
		$isDir = FALSE;
		if (is_file($value))
		{
			$isDir = TRUE;
		}
		elseif ($this->base && is_file($this->base . DIRECTORY_SEPARATOR . $value))
		{
			$isDir = TRUE;
		}
		return $isDir;
	}


	/**
	 * Return modified value
	 *
	 * @param mixed $value Filtered value
	 *
	 * @return mixed
	 */
	protected function useFilter($value)
	{
		// base is set and value not full path
		if ($this->base && !preg_match('/^([a-zA-Z]:\\\|\/)/', $value))
		{
			$value = $this->make($this->base, $value , '/');
		}

		return $value;
	}


	/**
	 * Make path from list of arguments.
	 *
	 * @return string
	 */
	private function make()
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
