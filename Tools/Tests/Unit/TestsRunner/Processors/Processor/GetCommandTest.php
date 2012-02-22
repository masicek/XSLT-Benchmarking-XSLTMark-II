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
 * GetCommandTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getCommand
 */
class GetCommandTest extends TestCase
{


	public function test()
	{
		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Processor', 'getCommand');
		$method->setAccessible(TRUE);
		$command = $method->invokeArgs($processor, array(
			'[PROCESSORS] Lorem [LIBS] [XSLT] ipsum [INPUT][OUTPUT] dolor [ERROR]',
			'Template path',
			'Input path',
			'Output path',
			'Error path',
		));

		$libs = $this->setDirSep(LIBS_TOOLS);
		$processors = $this->setDirSep(LIBS_TOOLS . '/Processors');
		$this->assertEquals($processors . ' Lorem ' . $libs . ' Template path ipsum Input pathOutput path dolor Error path', $command);
	}


}
