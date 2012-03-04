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
 * @covers \XSLTBenchmarking\TestsRunner\WindowsMemoryUsageDriver::__construct
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
		$command = $memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);
		$memory = $memoryUsage->get();
		$this->assertFileNotExists($logFile);

		$this->assertGreaterThan(1000, $memory);
	}


	public function testStderr_WithQuotes()
	{
		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "echo aaa\';" 2> "' . __FILE__ . '.error"';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$this->assertFileNotExists(__FILE__ . '.error');
		$command = $memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);
		$memory = $memoryUsage->get();
		$this->assertFileNotExists($logFile);

		$this->assertFileExists(__FILE__ . '.error');
		$error = file_get_contents(__FILE__ . '.error');
		unlink(__FILE__ . '.error');
		$this->assertNotRegExp('/Peak Working Set Size \(kbytes\):/', $error);
		$this->assertRegExp('/PHP Parse error:/', $error);

		$this->assertGreaterThan(1000, $memory);
	}


	public function testStderr_WithoutQuotes()
	{
		$memoryUsage = new WindowsMemoryUsageDriver(__DIR__);
		$command = 'php -r "echo aaa\';" 2> ' . __FILE__ . '.error';
		$logFile = $this->setDirSep(__DIR__ . '/windowsMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$this->assertFileNotExists(__FILE__ . '.error');
		$command = $memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);
		$memory = $memoryUsage->get();
		$this->assertFileNotExists($logFile);

		$this->assertFileExists(__FILE__ . '.error');
		$error = file_get_contents(__FILE__ . '.error');
		unlink(__FILE__ . '.error');
		$this->assertNotRegExp('/Peak Working Set Size \(kbytes\):/', $error);
		$this->assertRegExp('/PHP Parse error:/', $error);

		$this->assertGreaterThan(1000, $memory);
	}


}
