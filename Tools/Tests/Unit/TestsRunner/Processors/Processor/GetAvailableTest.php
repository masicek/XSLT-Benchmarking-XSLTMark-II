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
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getAvailable
 * @covers \XSLTBenchmarking\TestsRunner\Processor::detectAvailable
 * @covers \XSLTBenchmarking\TestsRunner\AProcessorDriver::getName
 */
class GetAvailableTest extends TestCase
{


	public function test()
	{
		$processor = new Processor(
			__DIR__,
			__DIR__ . '/FixtureDrivers',
			'\Tests\XSLTBenchmarking\TestsRunner\Processor\\'
		);

		$available = $processor->getAvailable();

		$this->assertEquals(2, count($available));
		$this->assertArrayHasKey('first', $available);
		$this->assertArrayHasKey('second', $available);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\TestsRunner\Processor\FirstProcessorDriver', $available['first']);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\AProcessorDriver', $available['first']);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\TestsRunner\Processor\SecondProcessorDriver', $available['second']);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\AProcessorDriver', $available['second']);
	}


}
