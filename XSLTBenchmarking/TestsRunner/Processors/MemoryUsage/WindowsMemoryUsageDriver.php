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
 * Windows driver for geting maximum memory usage of command excuteb by 'timemem.exe'
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class WindowsMemoryUsageDriver extends AMemoryUsageDriver
{


	/**
	 * Path of log file for reporting measured PeakWorkingSetSize from '/usr/bin/time'
	 *
	 * @var string
	 */
	private $logPath;

	/**
	 * Path of command 'timemem.exe' for measure memory usage
	 *
	 * @var string
	 */
	private $timememPath;

	/**
	 * Helpful variable for saving path of file for redirecting stderr in input command
	 *
	 * @var string
	 */
	private $errorOutputPath;


	/**
	 * Construct path of main and end log files
	 *
	 * @param type $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir)
	{
		parent::__construct($tmpDir);

		$this->logPath = P::m($this->tmpDir, 'windowsMemoryUsage.log');
		$this->timememPath = P::m(LIBS, 'Timemem', 'timemem.exe');
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
			throw new \XSLTBenchmarking\InvalidArgumentException('Windows memory usage log file exist.');
		}

		// error output
		preg_match('/2> *"([^"]*)"/', $command, $matches);
		$this->errorOutputPath = NULL;
		if (isset($matches[1]))
		{
			$command = str_replace($matches[0], '', $command);
			$this->errorOutputPath = $matches[1];
		}
		else
		{
			preg_match('/2> *([^ ]*)/', $command, $matches);
			if (isset($matches[1]))
			{
				$command = str_replace($matches[0], '', $command);
				$this->errorOutputPath = $matches[1];
			}
		}

		$command = str_replace('>', '^>', $command);
		$command = str_replace('"', '\\"', $command);
		$command = $this->timememPath . ' "' . $command . '" 2> ' . $this->logPath;

		return $command;
	}


	/**
	 * Return maximum memory usage (in bytes) last checked command by self::run().
	 *
	 * @return integer
	 */
	public function get()
	{
		$log = trim(file_get_contents($this->logPath));

		// error output of command
		$errorEndPos = strpos($log, 'Process ID:');
		if ($errorEndPos)
		{
			$error = substr($log, 0, $errorEndPos);
			$log = substr($log, $errorEndPos);
			file_put_contents($this->errorOutputPath, $error);
		}

		$log = preg_match('/Peak Working Set Size \(kbytes\): ([0-9]*)/', $log, $matches);
		$maxMemory = $matches[1];

		unlink($this->logPath);

		// units corrections (Kilobytes -> Bytes)
		$maxMemory = $maxMemory * 1000;

		return $maxMemory;
	}


}
