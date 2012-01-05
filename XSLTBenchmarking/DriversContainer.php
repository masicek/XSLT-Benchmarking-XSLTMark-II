<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking;

require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Exceptions.php';

use PhpPath\P;

/**
 * Class for better work with drivers.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
abstract class DriversContainer
{


	/**
	 * Instance of selected driver
	 *
	 * @var IDriver|ADriver
	 */
	protected $driver = NULL;

	/**
	 * Arguments for construct of each driver
	 *
	 * @var array
	 */
	private $args = array();


	/**
	 * Save arguments for pass into each driver
	 */
	public function __construct()
	{
		$this->args = func_get_args();
	}


	/**
	 * Make new object of driver selected by name.
	 *
	 * @param string $driverName Name of driver for using
	 *
	 * @return this
	 */
	public function setDriver($driverName)
	{
		// create driver file name
		$driverName = ucfirst(strtolower($driverName));
		$driverNameSuffix = $this->getDriversNamesSuffix();
		$fileName = $driverName . $driverNameSuffix . '.php';

		// require selected driver
		$filePath = P::mcf($this->getDriversDirectory(), $fileName);
		require_once $filePath;

		// create class name
		$driverNamespace = $this->getDriversNamespace();
		if ($driverNamespace && ($driverNamespace[strlen($driverNamespace) - 1] !== '\\'))
		{
			$driverNamespace = $driverNamespace . '\\';
		}
		$className = $driverNamespace . $driverName . $driverNameSuffix;

		// create new instance of driver with parameters
		$reflection = new \ReflectionClass($className);
		$args = func_get_args();
		unset($args[0]);
		$args = array_merge($this->args, $args); // TODO do test for join arguments
		$this->driver = $reflection->newInstanceArgs($args);

		return $this;
	}


	/**
	 * Call drivers method.
	 *
	 * @param string $name Name of method
	 * @param array $arguments List of arguments for calling method
	 *
	 * @throws \XSLTBenchmarking\UnknownMethodException Unknown method on set driver
	 * @return mix
	 */
	public function __call($name, $arguments)
	{
		if (!in_array($name, get_class_methods($this->driver)))
		{
			throw new \XSLTBenchmarking\UnknownMethodException('On driver "' . get_class($this->driver) . '" is not method "' . $name . '"');
		}

		return call_user_func_array(array($this->driver, $name), $arguments);
	}


	/**
	 * Return directory containing drivers.
	 *
	 * @return string
	 */
	protected function getDriversDirectory()
	{
		$reflection = new \ReflectionClass($this);
		return dirname($reflection->getFileName());
	}


	/**
	 * Return namespace of drivers
	 *
	 * @return string
	 */
	protected function getDriversNamespace()
	{
		$reflection = new \ReflectionClass($this);
		return $reflection->getNamespaceName();
	}


	/**
	 * Return drivers suffix of name
	 *
	 * @return string
	 */
	protected function getDriversNamesSuffix()
	{
		$reflection = new \ReflectionClass($this);
		return $reflection->getShortName() . 'Driver';
	}


}
