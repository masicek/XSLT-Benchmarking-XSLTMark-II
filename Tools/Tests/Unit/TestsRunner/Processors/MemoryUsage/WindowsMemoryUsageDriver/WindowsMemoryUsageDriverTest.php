<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\WindowsMemoryUsageDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\WindowsMemoryUsageDriver;

require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/WindowsMemoryUsageDriver.php';

/**
 * WindowsMemoryUsageDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\WindowsMemoryUsageDriver::run
 * @covers \XSLTBenchmarking\TestsRunner\WindowsMemoryUsageDriver::get
 */
class WindowsMemoryUsageDriverTest extends TestCase
{

	public function setUp()
	{
		parent::setUp();

		if (PHP_OS != 'WINNT')
		{
			$this->markTestSkipped('This tests are only for Windows');
		}
	}


	public function testOk()
	{
		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "sleep(1);"';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);
		$memory = $memoryUsage->get();
		$this->assertFileNotExists($logFile);

		$this->assertGreaterThan(1000, $memory);
	}


	public function testWithoutRunnigCommand_LongWaitingForEndBackgroudProcess()
	{
		$this->markTestSkippedCondition();

		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "sleep(1);"';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$memoryUsage->run($command);

		sleep(1);

		$this->assertFileExists($logFile);

		try {
			$memory = $memoryUsage->get();
			$this->fail();
		} catch (\XSLTBenchmarking\LongLoopException $e) {
			$this->assertEquals('Loop waiting for end of background process have to many iteratins', $e->getMessage());
			sleep(10);
			unlink($logFile);
			unlink($logFile . '.end');
		}
	}


	public function testWithoutRunnigCommand_LongWaitingForStartOfCommandInBackgroudProcess()
	{
		$this->markTestSkippedCondition();

		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "sleep(1);"';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$memoryUsage->run($command);

		sleep(10);

		$this->assertFileExists($logFile);

		try {
			$memory = $memoryUsage->get();
			$this->fail();
		} catch (\XSLTBenchmarking\LongLoopException $e) {
			$this->assertEquals('Loop in background process was too long - before running', $e->getMessage());
			unlink($logFile);
			unlink($logFile . '.end');
		}
	}


	public function testWithoutRunnigCommand_LongWaitingForLongCommandInBackgroudProcess()
	{
		$this->markTestSkippedCondition();

		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "sleep(150);"';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);

		try {
			$memory = $memoryUsage->get();
			$this->fail();
		} catch (\XSLTBenchmarking\LongLoopException $e) {
			$this->assertEquals('Loop in background process was too long - running', $e->getMessage());
			unlink($logFile);
			unlink($logFile . '.end');
		}
	}


}
