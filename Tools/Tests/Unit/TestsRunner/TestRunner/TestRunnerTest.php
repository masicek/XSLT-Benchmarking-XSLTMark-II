<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\TestRunner;

require_once ROOT_TOOLS . '/TestsRunner/TestRunner.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\TestRunner;

/**
 * TestRunnerTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\TestRunner::__construct
 */
class TestRunnerTest extends TestCase
{

	private $factory;
	private $processor;

	public function setUp()
	{
		$this->factory = $this->getMock('\XSLTBenchmarking\Factory');
		$this->processor = $this->getMock('XSLTBenchmarking\TestsRunner\Processor', array('getAvailable'));
		$this->processor->expects($this->any())
			->method('getAvailable')
			->will($this->returnValue(array(
				'processor1' => 'prcessor1.php',
				'processor2' => 'prcessor2.sh',
				'processor3' => 'prcessor3.sh')
			));
	}

	public function testSettingsWithoutProccesors()
	{
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			array(),
			array(),
			123,
			__DIR__
		);

		$this->assertEquals($this->factory, $this->getPropertyValue($runner, 'factory'));
		$this->assertEquals($this->processor, $this->getPropertyValue($runner, 'processor'));
		$this->assertEquals(123, $this->getPropertyValue($runner, 'repeating'));
		$this->assertEquals(__DIR__, $this->getPropertyValue($runner, 'tmpDir'));
	}

	public function testWrongTmpDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			array(),
			array(),
			123,
			$this->setDirSep(__DIR__ . '/unknown')
		);
	}


	public function testAllProcessors()
	{
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			TRUE,
			array(),
			123,
			__DIR__
		);

		$this->assertEquals(
			array('processor1', 'processor2', 'processor3'),
			$this->getPropertyValue($runner, 'processorsNames')
		);
	}


	public function testAllProcessorsWithExclude()
	{
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			TRUE,
			array('processor2'),
			123,
			__DIR__
		);

		$this->assertEquals(
			array('processor1', 'processor3'),
			$this->getPropertyValue($runner, 'processorsNames')
		);
	}


	public function testSelectedProcessors()
	{
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			array('processor2', 'processor3'),
			array(),
			123,
			__DIR__
		);

		$this->assertEquals(
			array('processor2', 'processor3'),
			$this->getPropertyValue($runner, 'processorsNames')
		);
	}


	public function testSelectedProcessorsWithExclude()
	{
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			array('processor2', 'processor3'),
			array('processor2'),
			123,
			__DIR__
		);

		$this->assertEquals(
			array('processor3'),
			$this->getPropertyValue($runner, 'processorsNames')
		);
	}


	public function testUnknownProcessors()
	{
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$runner = new TestRunner(
			$this->factory,
			$this->processor,
			array('unknown'),
			array(),
			123,
			__DIR__
		);
	}


}
