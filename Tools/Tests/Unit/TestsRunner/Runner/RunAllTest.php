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
 * RunAllTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Runner::runAll
 */
class RunAllTest extends TestCase
{


	/**
	 * @dataProvider provider
	 */
	public function test($verbose)
	{
		$runner = new Runner(
			$this->getMock('\XSLTBenchmarking\Factory'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\Params'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\TestRunner', array(), array(), '', FALSE),
			$this->getMock('\XSLTBenchmarking\Reports\Printer', array(), array(), '', FALSE),
			__DIR__
		);

		$test1 = $this->getMock('\XSLTBenchmarking\TestsRunner\Test', array('getName'), array(), '', FALSE);
		$test1->expects($this->any())->method('getName')->will($this->returnValue('Test name 1'));
		$test2 = $this->getMock('\XSLTBenchmarking\TestsRunner\Test', array('getName'), array(), '', FALSE);
		$test2->expects($this->any())->method('getName')->will($this->returnValue('Test name 2'));
		$tests = array($test1, $test2);

		$reports = array(
			$this->getMock('\XSLTBenchmarking\Reports\Report', array(), array(), '', FALSE),
			$this->getMock('\XSLTBenchmarking\Reports\Report', array(), array(), '', FALSE),
		);

		$testRunner = \Mockery::mock('\XSLTBenchmarking\TestsRunner\TestRunner');
		$testRunner->shouldReceive('run')->once()->with($tests[0])->andReturn($reports[0]);
		$testRunner->shouldReceive('run')->once()->with($tests[1])->andReturn($reports[1]);

		$printer = \Mockery::mock('\XSLTBenchmarking\Reports\Printer');
		$printer->shouldReceive('addReport')->once()->with($reports[0]);
		$printer->shouldReceive('addReport')->once()->with($reports[1]);
		$printer->shouldReceive('printAll')->once()->andReturn('Path of report');

		$this->setPropertyValue($runner, 'tests', $tests);
		$this->setPropertyValue($runner, 'testRunner', $testRunner);
		$this->setPropertyValue($runner, 'reportsPrinter', $printer);

		ob_start();
		$reportFilePath = $runner->runAll($verbose);
		$output = ob_get_clean();

		$this->assertEquals('Path of report', $reportFilePath);

		if ($verbose)
		{
			$this->assertEquals(
				'1/2 - Runnig of the test "Test name 1" done.' . PHP_EOL .
				'2/2 - Runnig of the test "Test name 2" done.' . PHP_EOL,
				$output
			);
		}
		else
		{
			$this->assertEquals('', $output);
		}

	}


	public function provider($verbose)
	{
		return array(
			'verbose' => array(TRUE),
			'not verbose' => array(FALSE)
		);
	}


}
