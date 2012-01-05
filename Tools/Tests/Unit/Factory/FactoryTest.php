<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Factory;

require_once ROOT_TOOLS . '/Factory.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Factory;

/**
 * FactoryTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class FactoryTest extends TestCase
{


	/**
	 * @covers \XSLTBenchmarking\Factory::getTestsGeneratorTest
	 */
	public function testGetTestsGeneratorTest()
	{
		$factory = new Factory();
		$test = $factory->getTestsGeneratorTest('Test name');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\Test', $test);
		$this->assertEquals('Test name', $test->getName());
	}


	/**
	 * @covers \XSLTBenchmarking\Factory::getTestsRunnerTest
	 */
	public function testGetTestsRunnerTest()
	{
		$factory = new Factory();
		$test = $factory->getTestsRunnerTest('Test name');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Test', $test);
		$this->assertEquals('Test name', $test->getName());
	}


}
