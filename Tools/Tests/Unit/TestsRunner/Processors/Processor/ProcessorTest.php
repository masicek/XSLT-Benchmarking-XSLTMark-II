<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Processor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Processor;
use \XSLTBenchmarking\TestsRunner\MemoryUsage;

require_once ROOT_TOOLS . '/TestsRunner/Processors/Processor.php';
require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/MemoryUsage.php';

/**
 * ProcessorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Processor::__construct
 */
class ProcessorTest extends TestCase
{


	public function testDefault()
	{
		$reflection = new \ReflectionClass('\XSLTBenchmarking\TestsRunner\Processor');
		$driversDir = $this->setDirSep(dirname($reflection->getFileName()) . '/Drivers');

		$memoryUsage = new MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$this->assertEquals(__DIR__, $this->getPropertyValue($processor, 'tmpDir'));
		$this->assertEquals($memoryUsage, $this->getPropertyValue($processor, 'memoryUsage'));
		$this->assertEquals($driversDir, $this->getPropertyValue($processor, 'driversDir'));
		$this->assertEquals('\XSLTBenchmarking\TestsRunner\\', $this->getPropertyValue($processor, 'driversNamespace'));
	}


	public function testOk()
	{
		$memoryUsage = new MemoryUsage(__DIR__);
		$processor = new Processor(
			__DIR__,
			$memoryUsage,
			__DIR__ . '/FixtureDrivers',
			'\Tests\XSLTBenchmarking\TestsRunner\Processor\\'
		);
		$this->assertEquals(__DIR__, $this->getPropertyValue($processor, 'tmpDir'));
		$this->assertEquals($memoryUsage, $this->getPropertyValue($processor, 'memoryUsage'));
		$this->assertEquals($this->setDirSep(__DIR__ . '/FixtureDrivers'), $this->getPropertyValue($processor, 'driversDir'));
		$this->assertEquals('\Tests\XSLTBenchmarking\TestsRunner\Processor\\', $this->getPropertyValue($processor, 'driversNamespace'));
	}


	public function testWrongTmp()
	{
		$memoryUsage = new MemoryUsage(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$processor = new Processor(__DIR__ . '/unknown/', $memoryUsage);
	}


	public function testWrongDriversDir()
	{
		$memoryUsage = new MemoryUsage(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$processor = new Processor(__DIR__, $memoryUsage, __DIR__ . '/unknown/');
	}


}
