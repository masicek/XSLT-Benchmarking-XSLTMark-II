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
 * Windows driver for geting maximum memory usage of command excuteb by 'exec'
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class WindowsMemoryUsageDriver extends AMemoryUsageDriver
{


	/**
	 * Length of substring of command that will by getted for filtering in 'wmic'
	 */
	const COMMAND_SUBSTR_LENGTH = 150;

	/**
	 * Microsecods waitig intervail in waiting loops
	 */
	const WAITING_MICROSECONDS = 1000;

	/**
	 * Maximum iterations in waiting loops
	 */
	const WAITING_MAX_LOOPS = 1000;


	/**
	 * Path of log file for reporting measured PeakWorkingSetSize from 'wmic'
	 *
	 * @var string
	 */
	private $logPathMain;

	/**
	 * Path of log file for reporting end of background process for measured PeakWorkingSetSize from 'wmic'
	 *
	 * @var string
	 */
	private $logPathEnd;


	/**
	 * Construct path of main and end log files
	 *
	 * @param type $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir)
	{
		parent::__construct($tmpDir);

		$this->logPathMain = P::m($this->tmpDir, 'windowsMemoryUsage.log');
		$this->logPathEnd = $this->logPathMain . '.end';
	}


	/**
	 * Run command on backend, that checking memory usage of getted command.
	 * After ending of set command, run command have to end to.
	 *
	 * @param string $command Checked command
	 *
	 * @throws \XSLTBenchmarking\LongLoopException Long waiting for deletenig main log file
	 * @return void
	 */
	public function run($command)
	{

		// wait for deleting log file by before process
		$loopCounter = 0;
		while (is_file($this->logPathMain))
		{
			if ($loopCounter >= self::WAITING_MAX_LOOPS)
			{
				throw new \XSLTBenchmarking\LongLoopException('Loop waiting for deleting log file have to many iteratins');
			}

			$loopCounter++;
			usleep(self::WAITING_MICROSECONDS);
		}

		$commandSubstr = $this->getCommandSubstr($command);

		// run batch file in background
		$batPath = P::m(__DIR__, 'windowsMemoryUsage.bat');
		$command = 'cmd /C ' . $batPath . ' "' . $commandSubstr . '" "' . $this->logPathMain . '" "' . $this->logPathEnd . '"';
		$WshShell = new \COM("WScript.Shell");
		$oExec = $WshShell->Run($command, 0, FALSE);
	}


	/**
	 * Return maximum memory usage (in bytes) last checked command by self::run().
	 *
	 * @throws \XSLTBenchmarking\Exception Empty main log
	 * @throws \XSLTBenchmarking\Exception Long has no relevant value
	 * @return integer
	 */
	public function get()
	{
		$content = $this->getLogComplete();

		if (!$content)
		{
			throw new \XSLTBenchmarking\Exception('Empty log for checking memory usage of process');
		}

		$lines = explode(PHP_EOL, $content);
		array_walk($lines, function (&$item, $key) {$item = (int)$item;});
		$lines = array_filter($lines);
		if (count($lines) == 0)
		{
			throw new \XSLTBenchmarking\Exception('Log for checking memory usage of process has no relevant value');
		}
		rsort($lines, SORT_NUMERIC);
		$maxMemory = $lines[0];

		// units corrections (Kilobytes -> Bytes)
		$maxMemory = $maxMemory * 1000;

		return $maxMemory;
	}


	/**
	 * Return substring of command that can be used for selecting process from process list.
	 * Substring cannot include quotes (").
	 *
	 * @param string $command Parsed command
	 *
	 * @return string
	 */
	private function getCommandSubstr($command)
	{
		$command = trim($command);
		if ($command[0] == '"')
		{
			$command = substr($command, 1);
		}
		$command = trim($command);

		$endIndexApostrophe = strpos($command, '"');
		$endIndexEol = strpos($command, "\n");

		if (!$endIndexApostrophe && !$endIndexEol)
		{
			$endIndex = 0;
		}
		elseif (!$endIndexApostrophe && $endIndexEol)
		{
			$endIndex = $endIndexEol;
		}
		elseif ($endIndexApostrophe && !$endIndexEol)
		{
			$endIndex = $endIndexApostrophe;
		}
		else
		{
			$endIndex = min(array($endIndexApostrophe, $endIndexEol));
		}

		if (!$endIndex || $endIndex > self::COMMAND_SUBSTR_LENGTH)
		{
			$endIndex = self::COMMAND_SUBSTR_LENGTH;
		}

		$command = substr($command, 0, $endIndex);

		// add space between first and second parts - wmic behavior
		$spaceIndex = strpos($command, ' ');
		if ($spaceIndex !== FALSE)
		{
			$commandBegin = substr($command, 0, $spaceIndex);
			$commandEnd = substr($command, $spaceIndex);
			$command = $commandBegin . ' ' . $commandEnd;
		}

		// escape all backslashes (\)
		$command = str_replace('\\', '\\\\', $command);

		return $command;
	}


	/**
	 * Return content of main log after its completition.
	 *
	 * @throws \XSLTBenchmarking\LongLoopException Too long waiting for complete log
	 * @throws \XSLTBenchmarking\LongLoopException Background process run too long
	 * @return string
	 */
	private function getLogComplete()
	{
		// waiting for end of background process
		$loopCounter = 0;
		while (!is_file($this->logPathEnd))
		{
			if ($loopCounter >= self::WAITING_MAX_LOOPS)
			{
				throw new \XSLTBenchmarking\LongLoopException('Loop waiting for end of background process have to many iteratins');
			}

			$loopCounter++;
			usleep(self::WAITING_MICROSECONDS);
		}

		// waiting for complete end file
		$endIsComplete = FALSE;
		$loopCounter = 0;
		while (!$endIsComplete)
		{
			if ($loopCounter >= self::WAITING_MAX_LOOPS)
			{
				throw new \XSLTBenchmarking\LongLoopException('Loop waiting for completle end file of background process have to many iteratins');
			}
			$loopCounter++;
			usleep(self::WAITING_MICROSECONDS);

			$contentEnd = file_get_contents($this->logPathEnd);
			$contentEnd = explode(PHP_EOL, trim($contentEnd));
			$info = trim(end($contentEnd));
			if ($info == 'END')
			{
				$endIsComplete = TRUE;
			}
		}

		// backgroud process end premature
		$info = trim($contentEnd[0]);
		if ($info == 'LONG_LOOP_BEFORE')
		{
			throw new \XSLTBenchmarking\LongLoopException('Loop in background process was too long - before running');
		}
		if ($info == 'LONG_LOOP_RUNNING')
		{
			throw new \XSLTBenchmarking\LongLoopException('Loop in background process was too long - running');
		}
		unlink($this->logPathEnd);

		// get content of main log
		$content = file_get_contents($this->logPathMain);
		$content = mb_convert_encoding($content, 'UTF-8', 'UNICODE');
		unlink($this->logPathMain);

		return $content;
	}


}
