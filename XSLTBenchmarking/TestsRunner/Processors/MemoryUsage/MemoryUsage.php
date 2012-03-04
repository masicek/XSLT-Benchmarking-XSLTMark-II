<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once ROOT . '/DriversContainer.php';

/**
 * Class for geting memory usage of command excuteb by 'exec'
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class MemoryUsage extends \XSLTBenchmarking\DriversContainer
{

	/**
	 * Value of PHP_OS for Windows
	 */
	const OS_WIN = 'WINNT';

	/**
	 * Value of PHP_OS for Linux
	 */
	const OS_LINUX = 'Linux';


	/**
	 * List of possible drivers
	 *
	 * @var array
	 */
	private $driversNames = array(
		self::OS_WIN => 'Windows',
		self::OS_LINUX => 'Linux',
	);


	/**
	 * Prepare checking memory usage of set command.
	 * Drivers can return modified commad for better checking.
	 *
	 * @param string $command Checked command
	 *
	 * @return string
	 */
	public function run($command)
	{
		$this->setDriver($this->driversNames[PHP_OS]);
		return parent::run($command);
	}


}
