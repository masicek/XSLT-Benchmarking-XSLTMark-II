<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Processors;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Processor;
use \XSLTBenchmarking\TestsRunner\AProcessorDriver;

require_once ROOT_TOOLS . '/TestsRunner/Processors/Processor.php';
require_once ROOT_TOOLS . '/TestsRunner/Processors/Drivers/AProcessorDriver.php';
require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/MemoryUsage.php';

/**
 * KernelsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class KernelsTest extends TestCase
{


	public function test()
	{
		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$processorsDrivers = $processor->getAvailable();
		$possibleKernels = array(
			AProcessorDriver::KERNEL_LIBXSLT,
			AProcessorDriver::KERNEL_SABLOTRON,
			AProcessorDriver::KERNEL_SAXON,
			AProcessorDriver::KERNEL_XALAN,
		);

		foreach ($processorsDrivers as $processorDriver)
		{
			$message = 'Error during transformation by "' . $processorDriver->getFullName() . '"';
			$this->assertContains($processorDriver->getKernel(), $possibleKernels, $message);
		}
	}


}
