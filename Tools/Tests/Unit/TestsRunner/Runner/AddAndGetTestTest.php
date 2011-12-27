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
use \XSLTBenchmarking\TestsRunner\Test;


require_once ROOT_TOOLS . '/TestsRunner/Runner.php';
require_once ROOT_TOOLS . '/TestsRunner/Test.php';



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


	public function testOk()
	{
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/test.xslt'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/firstIn.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/firstOut.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/secondIn.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/secondOut.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/test2.xslt'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/lorem.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/ipsum.xml'), '');

		$runner = new Runner(__DIR__);
		$runner->addTest('Fixture');
		$runner->addTest('Fixture', '__params2.xml');
		$tests = $runner->getTests();

		$expectedTests = array();
		// first test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep(__DIR__ . '/Fixture/test.xslt'));
		$this->setPropertyValue($test, 'couples', array(
			$this->setDirSep(__DIR__ . '/Fixture/firstIn.xml') => $this->setDirSep(__DIR__ . '/Fixture/firstOut.xml'),
			$this->setDirSep(__DIR__ . '/Fixture/secondIn.xml') => $this->setDirSep(__DIR__ . '/Fixture/secondOut.xml'),
		));
		$expectedTests['Test name'] = $test;

		// second test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name 2');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep(__DIR__ . '/Fixture/test2.xslt'));
		$this->setPropertyValue($test, 'couples', array(
			$this->setDirSep(__DIR__ . '/Fixture/lorem.xml') => $this->setDirSep(__DIR__ . '/Fixture/ipsum.xml'),
		));
		$expectedTests['Test name 2'] = $test;

		$this->assertEquals($expectedTests, $tests);

		unlink($this->setDirSep(__DIR__ . '/Fixture/test.xslt'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/firstIn.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/firstOut.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/secondIn.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/secondOut.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/test2.xslt'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/lorem.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/ipsum.xml'));
	}


	public function testUnknownTestDir()
	{
		$runner = new Runner(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$runner->addTest('unknownTestsDir');
	}


	public function testUnknownParamsFile()
	{
		$runner = new Runner(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$runner->addTest('Fixture', 'unknownParams.xml');
	}


	public function testNameCollision()
	{
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/test.xslt'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/firstIn.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/firstOut.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/secondIn.xml'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/Fixture/secondOut.xml'), '');

		$runner = new Runner(__DIR__);
		$runner->addTest('Fixture');

		unlink($this->setDirSep(__DIR__ . '/Fixture/test.xslt'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/firstIn.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/firstOut.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/secondIn.xml'));
		unlink($this->setDirSep(__DIR__ . '/Fixture/secondOut.xml'));

		$this->setExpectedException('\XSLTBenchmarking\CollisionException', 'Duplicate name of test "Test name"');
		$runner->addTest('Fixture');
	}


}
