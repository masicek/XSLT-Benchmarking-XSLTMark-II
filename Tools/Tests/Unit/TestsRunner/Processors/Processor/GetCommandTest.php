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
		$processor = new Processor(__DIR__);
		$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Processor', 'getCommand');
		$method->setAccessible(TRUE);
		$command = $method->invokeArgs($processor, array(
			'[LIBS] Lorem [XSLT] ipsum [INPUT][OUTPUT] dolor [ERROR]',
			'Template path',
			'Input path',
			'Output path',
			'Error path',
		));

		$libs = $this->setDirSep(LIBS_TOOLS . '/Processors');
		$this->assertEquals($libs . ' Lorem Template path ipsum Input pathOutput path dolor Error path', $command);
	}


}
