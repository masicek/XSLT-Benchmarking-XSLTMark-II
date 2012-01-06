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
 * AddAndGetTestTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Runner::addTest
 * @covers \XSLTBenchmarking\TestsRunner\Runner::getTests
 */
class AddAndGetTestTest extends TestCase
{

	private $runner;

	public function setUp()
	{
		mkdir($this->setDirSep(__DIR__ . '/AAA'));
		mkdir($this->setDirSep(__DIR__ . '/BBB'));
		file_put_contents($this->setDirSep(__DIR__ . '/AAA/testParams'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/BBB/testParams'), '');

		$this->runner = new Runner(
			$this->getMock('\XSLTBenchmarking\Factory'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\Params'),
			$this->getMock('\XSLTBenchmarking\TestsRunner\TestRunner', array(), array(), '', FALSE),
			$this->getMock('\XSLTBenchmarking\Reports\Printer'),
			__DIR__
		);
	}

	public function tearDown()
	{
		unlink($this->setDirSep(__DIR__ . '/AAA/testParams'));
		unlink($this->setDirSep(__DIR__ . '/BBB/testParams'));
		rmdir($this->setDirSep(__DIR__ . '/AAA'));
		rmdir($this->setDirSep(__DIR__ . '/BBB'));
	}

	public function testOk()
	{
		$factory = \Mockery::mock('\XSLTBenchmarking\Factory');
		$factory->shouldReceive('getTestsRunnerTest')->once()->with('Test name 1')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Test');
				$test->shouldReceive('setTemplatePath')->once()->andReturn('Test template path 1');
				$test->shouldReceive('addCouplesPaths')->once()->andReturn(array('Test couples paths 1'));
				return $test;
			}
		);
		$factory->shouldReceive('getTestsRunnerTest')->once()->with('Test name 2')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Test');
				$test->shouldReceive('setTemplatePath')->once()->andReturn('Test template path 2');
				$test->shouldReceive('addCouplesPaths')->once()->andReturn(array('Test couples paths 2'));
				return $test;
			}
		);

		$params = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Params');
		$params->shouldReceive('setFile')->once()->with($this->setDirSep(__DIR__ . '/AAA/testParams'));
		$params->shouldReceive('setFile')->once()->with($this->setDirSep(__DIR__ . '/BBB/testParams'));
		$params->shouldReceive('getName')->once()->andReturn('Test name 1');
		$params->shouldReceive('getName')->once()->andReturn('Test name 2');
		$params->shouldReceive('getTemplatePath')->once()->andReturn('Test template path 1');
		$params->shouldReceive('getTemplatePath')->once()->andReturn('Test template path 2');
		$params->shouldReceive('getCouplesPaths')->once()->andReturn(array('Test couples paths 1'));
		$params->shouldReceive('getCouplesPaths')->once()->andReturn(array('Test couples paths 2'));

		$this->setPropertyValue($this->runner, 'factory', $factory);
		$this->setPropertyValue($this->runner, 'params', $params);

		$this->runner->addTest('AAA', 'testParams');
		$this->runner->addTest('BBB', 'testParams');

		$tests = $this->runner->getTests();
		$this->assertEquals(2, count($tests));
		$this->assertArrayHasKey('Test name 1', $tests);
		$this->assertArrayHasKey('Test name 2', $tests);
	}


	public function testUnknownTestDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->runner->addTest('unknownTestsDir', 'testParams');
	}


	public function testUnknownParamsFile()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->runner->addTest('AAA', 'unknownParams.xml');
	}


	public function testNameCollision()
	{
		$factory = \Mockery::mock('\XSLTBenchmarking\Factory');
		$factory->shouldReceive('getTestsRunnerTest')->once()->with('Test name')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Test');
				$test->shouldReceive('setTemplatePath');
				$test->shouldReceive('addCouplesPaths');
				return $test;
			}
		);

		$params = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Params');
		$params->shouldReceive('setFile')->twice();
		$params->shouldReceive('getName')->twice()->andReturn('Test name');
		$params->shouldReceive('getTemplatePath')->once()->andReturn('Test template path');
		$params->shouldReceive('getCouplesPaths')->once()->andReturn(array('Test couples paths'));

		$this->setPropertyValue($this->runner, 'factory', $factory);
		$this->setPropertyValue($this->runner, 'params', $params);

		$this->runner->addTest('AAA', 'testParams');
		$this->setExpectedException('\XSLTBenchmarking\CollisionException', 'Duplicate name of test "Test name"');
		$this->runner->addTest('AAA', 'testParams');
	}


}
