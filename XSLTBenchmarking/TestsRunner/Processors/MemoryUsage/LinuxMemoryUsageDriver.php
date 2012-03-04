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
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class LinuxMemoryUsageDriver extends AMemoryUsageDriver
{


	/**
	 * Path of log file for reporting measured PeakWorkingSetSize from '/usr/bin/time'
	 *
	 * @var string
	 */
	private $logPath;

	/**
	 * Path of script included running command for running in '/usr/bin/time'
	 *
	 * @var string
	 */
	private $scriptPath;


	/**
	 * Construct path of main and end log files
	 *
	 * @param type $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir)
	{
		parent::__construct($tmpDir);

		$this->logPath = P::m($this->tmpDir, 'linuxMemoryUsage.log');
		$this->scriptPath = P::m($this->tmpDir, 'linuxMemoryUsage.sh');
	}


	/**
	 * Save command into scrit and return command for running set command and
	 * checking its memory usage.
	 *
	 * @param string $command Checked command
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Log/Script file exist
	 * @return string
	 */
	public function run($command)
	{
		if (is_file($this->logPath))
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('Linux memory usage log file exist.');
		}
		if (is_file($this->scriptPath))
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('Linux memory usage script file exist.');
		}

		file_put_contents($this->scriptPath, $command);
		chmod($this->scriptPath, 0777);

		$command =
			'/usr/bin/time -v ' . $this->scriptPath . ' 2>&1 | ' .
			'grep \'Maximum resident set size (kbytes):\' | ' .
			'sed \'s/^.*: //\' > ' . $this->logPath;

		return $command;
	}


	/**
	 * Return maximum memory usage (in bytes) last checked command by self::run().
	 *
	 * @return integer
	 */
	public function get()
	{
		$maxMemory = file_get_contents($this->logPath);
		$maxMemory = trim($maxMemory);

		unlink($this->logPath);
		unlink($this->scriptPath);

		// units corrections (Kilobytes -> Bytes)
		$maxMemory = $maxMemory * 1000;

		return $maxMemory;
	}


}
