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
		$dir = dirname($reflection->getFileName());

		$processor = new Processor();
		$this->assertEquals($dir, $this->getPropertyValue($processor, 'scriptsDir'));
	}


	public function testSetOk()
	{
		$processor = new Processor(__DIR__ . '/../');
		$this->assertEquals($this->setDirSep(__DIR__ . '/../'), $this->getPropertyValue($processor, 'scriptsDir'));
	}


	public function testSetUnknown()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$processor = new Processor(__DIR__ . '/unknown/');
	}


}
