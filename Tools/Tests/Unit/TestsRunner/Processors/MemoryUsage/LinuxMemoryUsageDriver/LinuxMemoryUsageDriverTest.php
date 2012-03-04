<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\LinuxMemoryUsageDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\LinuxMemoryUsageDriver;

require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/LinuxMemoryUsageDriver.php';

/**
 * LinuxMemoryUsageDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\LinuxMemoryUsageDriver::__construct
 * @covers \XSLTBenchmarking\TestsRunner\LinuxMemoryUsageDriver::run
 * @covers \XSLTBenchmarking\TestsRunner\LinuxMemoryUsageDriver::get
 */
class LinuxMemoryUsageDriverTest extends TestCase
{

	public function setUp()
	{
		parent::setUp();

		if (PHP_OS != 'Linux')
		{
			$this->markTestSkipped('This tests are only for Linux');
		}
	}


	public function testOk()
	{
		$memoryUsage = new LinuxMemoryUsageDriver(__DIR__);
		$command = 'php -r "sleep(1);"';
		$logFile = $this->setDirSep(__DIR__ . '/linuxMemoryUsage.log');

		$this->assertFileNotExists($logFile);
		$command = $memoryUsage->run($command);

		exec($command);

		$this->assertFileExists($logFile);
		$memory = $memoryUsage->get();
		$this->assertFileNotExists($logFile);

		$this->assertGreaterThan(1000, $memory);
	}


}
