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
		$processorDriver = $this->getMock('\XSLTBenchmarking\TestsRunner\AProcessorDriver', array('getCommandTemplate', 'getFullName', 'getKernel'), array(), '', FALSE);
		$processorDriver->expects($this->once())->method('getCommandTemplate')->will($this->returnValue('[LIBS] Lorem [XSLT] ipsum [INPUT][OUTPUT] dolor [ERROR]'));
		$processorDriver->expects($this->never())->method('getFullName')->will($this->returnValue(''));
		$processorDriver->expects($this->never())->method('getKernel')->will($this->returnValue(''));

		$processor = new Processor(__DIR__);
		$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Processor', 'getCommand');
		$method->setAccessible(TRUE);
		$command = $method->invokeArgs($processor, array(
			$processorDriver,
			'Template path',
			'Input path',
			'Output path',
			'Error path',
		));

		$libs = $this->setDirSep(LIBS_TOOLS . '/Processors');
		$this->assertEquals($libs . ' Lorem Template path ipsum Input pathOutput path dolor Error path', $command);
	}


}
