<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Generator;

require_once ROOT_TOOLS . '/TestsGenerator/Generator.php';
require_once ROOT_TOOLS . '/TestsGenerator/Test.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Generator;
use \XSLTBenchmarking\TestsGenerator\Test;

/**
 * AddAndGetTestsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\Generator::addTests
 * @covers XSLTBenchmarking\TestsGenerator\Generator::getTests
 */
class AddAndGetTestsTest extends TestCase
{

	private $generator;

	public function setUp()
	{
		$factory = $this->getMock('\XSLTBenchmarking\Factory');
		$params = $this->getMock('\XSLTBenchmarking\TestsGenerator\Params');
		$templating = $this->getMock('\XSLTBenchmarking\TestsGenerator\Templating');
		$paramsTest = $this->getMock('\XSLTBenchmarking\TestsRunner\Params');

		$this->generator = new Generator($factory, $params, $templating, $paramsTest, __DIR__, __DIR__);

		mkdir(__DIR__ . '/AAA');
		file_put_contents(__DIR__ . '/AAA/myParams', '');
	}


	public function tearDown()
	{
		unlink(__DIR__ . '/AAA/myParams');
		rmdir(__DIR__ . '/AAA');
	}


	public function testOk()
	{
		$factory = \Mockery::mock('\XSLTBenchmarking\Factory');
		$factory->shouldReceive('getTestsGeneratorTest')->once()->with('Test name - First')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Test');
				$test->shouldReceive('setTemplatePath')->once()->with('Test template path');
				$test->shouldReceive('setTemplatingType')->once()->with('Test templating type');
				$test->shouldReceive('setPath')->once()->with(__DIR__);
				$test->shouldReceive('addFilesPaths')->once()->with(array('Test files paths 1'));
				$test->shouldReceive('addSettings')->once()->with(array('Test settings 1'));
				$test->shouldReceive('setParamsFilePath')->once()->with('Test params file name 1');
				return $test;
			}
		);
		$factory->shouldReceive('getTestsGeneratorTest')->once()->with('Test name - Second')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Test');
				$test->shouldReceive('setTemplatePath')->once()->with('Test template path');
				$test->shouldReceive('setTemplatingType')->once()->with('Test templating type');
				$test->shouldReceive('setPath')->once()->with(__DIR__);
				$test->shouldReceive('addFilesPaths')->once()->with(array('Test files paths 2'));
				$test->shouldReceive('addSettings')->once()->with(array('Test settings 2'));
				$test->shouldReceive('setParamsFilePath')->once()->with('Test params file name 2');
				return $test;
			}
		);

		$params = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Params');
		$params->shouldReceive('setFile')->once()->with($this->setDirSep(__DIR__ . '/AAA/myParams'));
		$params->shouldReceive('getTemplateName')->once()->withAnyArgs()->andReturn('Test name');
		$params->shouldReceive('getTemplatePath')->once()->withAnyArgs()->andReturn('Test template path');
		$params->shouldReceive('getTemplatingType')->once()->withAnyArgs()->andReturn('Test templating type');
		$params->shouldReceive('getTestsNames')->once()->withAnyArgs()->andReturn(array('First', 'Second'));
		$params->shouldReceive('getTestFilesPaths')->once()->with('First')->andReturn(array('Test files paths 1'));
		$params->shouldReceive('getTestFilesPaths')->once()->with('Second')->andReturn(array('Test files paths 2'));
		$params->shouldReceive('getTestSettings')->once()->with('First')->andReturn(array('Test settings 1'));
		$params->shouldReceive('getTestSettings')->once()->with('Second')->andReturn(array('Test settings 2'));
		$params->shouldReceive('getTestParamsFileName')->once()->with('First')->andReturn('Test params file name 1');
		$params->shouldReceive('getTestParamsFileName')->once()->with('Second')->andReturn('Test params file name 2');

		$this->setPropertyValue($this->generator, 'factory', $factory);
		$this->setPropertyValue($this->generator, 'params', $params);

		$this->generator->addTests('AAA', 'myParams');
		$addedTests = $this->generator->getTests();

		$this->assertEquals(2, count($addedTests));
		$this->assertArrayHasKey('Test name - First', $addedTests);
		$this->assertArrayHasKey('Test name - Second', $addedTests);
	}


	public function testNameCollision()
	{
		$factory = \Mockery::mock('\XSLTBenchmarking\Factory');
		$factory->shouldReceive('getTestsGeneratorTest')->andReturnUsing(
			function () {
				$test = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Test');
				$test->shouldReceive('setTemplatePath');
				$test->shouldReceive('setTemplatingType');
				$test->shouldReceive('setPath');
				$test->shouldReceive('addFilesPaths');
				$test->shouldReceive('addSettings');
				$test->shouldReceive('setParamsFilePath');
				return $test;
			}
		);

		$params = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Params');
		$params->shouldReceive('getTemplateName')->once()->withAnyArgs()->andReturn('Test name');
		$params->shouldReceive('getTestsNames')->once()->withAnyArgs()->andReturn(array('Duplicate name', 'Duplicate name'));
		$params->shouldReceive('setFile');
		$params->shouldReceive('getTemplatePath');
		$params->shouldReceive('getTemplatingType');
		$params->shouldReceive('getTestFilesPaths')->andReturn(array());
		$params->shouldReceive('getTestSettings')->andReturn(array());
		$params->shouldReceive('getTestParamsFileName');

		$this->setPropertyValue($this->generator, 'factory', $factory);
		$this->setPropertyValue($this->generator, 'params', $params);

		$this->setExpectedException('\XSLTBenchmarking\CollisionException', 'Duplicate name of test "Test name - Duplicate name"');
		$this->generator->addTests('AAA', 'myParams');
	}


	public function testUnknownTemplateDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->generator->addTests('unknownTestsTeamplateDir', 'myParams');
	}


	public function testUnknownParamsFile()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->generator->addTests('AAA', 'unknownParamsFile');
	}


}
