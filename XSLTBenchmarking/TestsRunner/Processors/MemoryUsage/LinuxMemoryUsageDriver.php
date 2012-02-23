<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once __DIR__ . '/AMemoryUsageDriver.php';
require_once ROOT . '/Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Linux driver for geting maximum memory usage of command excuteb by 'exec'
 *
 * HACK - not implemented now
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class LinuxMemoryUsageDriver extends AMemoryUsageDriver
{


	/**
	 * Construct path of main and end log files
	 *
	 * @param type $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir)
	{
	}


	/**
	 * Run command on backend, that checking memory usage of getted command.
	 * After ending of set command, run command have to end to.
	 *
	 * @param string $command Checked command
	 *
	 * @return void
	 */
	public function run($command)
	{
	}


	/**
	 * Return maximum memory usage (in bytes) last checked command by self::run().
	 *
	 * @return integer
	 */
	public function get()
	{
		return 0;
	}


}
