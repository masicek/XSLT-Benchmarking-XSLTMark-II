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

require_once ROOT_TOOLS . '/TestsRunner/Processors/Processor.php';

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

		$processor = new Processor(__DIR__);
		$this->assertEquals(__DIR__, $this->getPropertyValue($processor, 'tmpDir'));
		$this->assertEquals($driversDir, $this->getPropertyValue($processor, 'driversDir'));
		$this->assertEquals('\XSLTBenchmarking\TestsRunner\\', $this->getPropertyValue($processor, 'driversNamespace'));
	}


	public function testOk()
	{
		$processor = new Processor(
			__DIR__,
			__DIR__ . '/FixtureDrivers',
			'\Tests\XSLTBenchmarking\TestsRunner\Processor\\'
		);
		$this->assertEquals(__DIR__, $this->getPropertyValue($processor, 'tmpDir'));
		$this->assertEquals($this->setDirSep(__DIR__ . '/FixtureDrivers'), $this->getPropertyValue($processor, 'driversDir'));
		$this->assertEquals('\Tests\XSLTBenchmarking\TestsRunner\Processor\\', $this->getPropertyValue($processor, 'driversNamespace'));
	}


	public function testWrongTmp()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$processor = new Processor(__DIR__ . '/unknown/');
	}


	public function testWrongDriversDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$processor = new Processor(__DIR__, __DIR__ . '/unknown/');
	}


}
