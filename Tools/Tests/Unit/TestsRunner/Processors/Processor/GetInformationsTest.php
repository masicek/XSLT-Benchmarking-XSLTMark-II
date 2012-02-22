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
require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/MemoryUsage.php';

/**
 * ProcessorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getInformations
 * @covers \XSLTBenchmarking\TestsRunner\Processor::readInformations
 * @covers \XSLTBenchmarking\TestsRunner\AProcessorDriver::getInformations
 */
class GetInformationsTest extends TestCase
{


	public function test()
	{
		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(
			__DIR__,
			$memoryUsage,
			__DIR__ . '/FixtureDrivers',
			'\Tests\XSLTBenchmarking\TestsRunner\Processor\\'
		);

		$this->assertEquals(array(
				'first' => array(
					'fullName' => 'First processor',
					'kernel' => 'First kernel',
				),
				'second' => array(
					'fullName' => 'Second processor',
					'kernel' => 'Second kernel',
				),
			),
			$processor->getInformations());
	}


}
