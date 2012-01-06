<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Runner;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Runner;

require_once ROOT_TOOLS . '/TestsRunner/Runner.php';


/**
 * RunnerTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Runner::__construct
 */
class RunnerTest extends TestCase
{


	public function testOk()
	{
		mkdir($this->setDirSep(__DIR__ . '/tests'));

		$runner = new Runner(
			$this->getMock('\XSLTBenchmarking\Factory'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\Params'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\TestRunner', array(), array(), '', FALSE),
			$this->getMock('\XSLTBenchmarking\Reports\Printer'),
			__DIR__ . '/tests'
		);
		$this->assertEquals($this->setDirSep(__DIR__ . '/tests'), $this->getPropertyValue($runner, 'testsDirectory'));

		rmdir($this->setDirSep(__DIR__ . '/tests'));
	}


	public function testNotExistDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$runner = new Runner(
			$this->getMock('\XSLTBenchmarking\Factory'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\Params'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\TestRunner', array(), array(), '', FALSE),
			$this->getMock('\XSLTBenchmarking\Reports\Printer'),
			__DIR__ . '/unknown'
		);
	}


}
