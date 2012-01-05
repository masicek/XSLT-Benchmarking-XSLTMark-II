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
 * @covers XSLTBenchmarking\TestsGenerator\Generator::generateAll
 * @covers XSLTBenchmarking\TestsGenerator\Generator::generateTest
 */
class GenerateAllTest extends TestCase
{


	public function testOk()
	{
		$factoryInit = $this->getMock('\XSLTBenchmarking\Factory');
		$paramsInit = $this->getMock('\XSLTBenchmarking\TestsGenerator\Params');
		$templatingInit = $this->getMock('\XSLTBenchmarking\TestsGenerator\Templating');
		$paramsTestInit = $this->getMock('\XSLTBenchmarking\TestsRunner\Params');

		$templating = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Templating');
		$templating->shouldReceive('setDriver')->once()->with('Test templating type 1');
		$templating->shouldReceive('setDriver')->once()->with('Test templating type 2');
		$templating->shouldReceive('generate')->once()->with('Test template path 1', 'Test xslt path 1', array('Test settings 1'));
		$templating->shouldReceive('generate')->once()->with('Test template path 2', 'Test xslt path 2', array('Test settings 2'));

		$paramsTest = \Mockery::mock('\XSLTBenchmarking\TestsRunner\Params');
		$paramsTest->shouldReceive('setFile')->once()->with('Test params file path 1');
		$paramsTest->shouldReceive('generate')->once()->with(
			'Test name 1',
			'Test XSLT name 1',
			array(
				__DIR__ . '/file.1.1' => __DIR__ . '/file.1.2',
				__DIR__ . '/file.2.1' => __DIR__ . '/file.2.2',
			)
		);
		$paramsTest->shouldReceive('setFile')->once()->with('Test params file path 2');
		$paramsTest->shouldReceive('generate')->once()->with(
			'Test name 2',
			'Test XSLT name 2',
			array(
				__DIR__ . '/file.3.1' => __DIR__ . '/file.3.2',
			)
		);


		$generator = new Generator($factoryInit, $paramsInit, $templatingInit, $paramsTestInit, __DIR__, __DIR__);
		$this->setPropertyValue($generator, 'templating', $templating);
		$this->setPropertyValue($generator, 'paramsTest', $paramsTest);

		// make tests
		$testsList = array();

		// first test
		$test = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Test');
		$test->shouldReceive('getTemplatingType')->once()->andReturn('Test templating type 1');
		$test->shouldReceive('getTemplatePath')->once()->andReturn('Test template path 1');
		$test->shouldReceive('getXsltPath')->once()->andReturn('Test xslt path 1');
		$test->shouldReceive('getSettings')->once()->andReturn(array('Test settings 1'));
		$test->shouldReceive('getPath')->once()->andReturn(__DIR__ . '/XYZ');
		$test->shouldReceive('getFilesPaths')->once()->andReturn(array(
			__DIR__ . '/file.1.1' => __DIR__ . '/file.1.2',
			__DIR__ . '/file.2.1' => __DIR__ . '/file.2.2',
		));
		$test->shouldReceive('getParamsFilePath')->once()->andReturn('Test params file path 1');
		$test->shouldReceive('getName')->once()->andReturn('Test name 1');
		$test->shouldReceive('getXsltName')->once()->andReturn('Test XSLT name 1');
		$testsList['Test name - First'] = $test;

		// second test
		$test = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\Test');
		$test->shouldReceive('getTemplatingType')->once()->andReturn('Test templating type 2');
		$test->shouldReceive('getTemplatePath')->once()->andReturn('Test template path 2');
		$test->shouldReceive('getXsltPath')->once()->andReturn('Test xslt path 2');
		$test->shouldReceive('getSettings')->once()->andReturn(array('Test settings 2'));
		$test->shouldReceive('getPath')->once()->andReturn(__DIR__ . '/ABC');
		$test->shouldReceive('getFilesPaths')->once()->andReturn(array(
			__DIR__ . '/file.3.1' => __DIR__ . '/file.3.2',
		));
		$test->shouldReceive('getParamsFilePath')->once()->andReturn('Test params file path 2');
		$test->shouldReceive('getName')->once()->andReturn('Test name 2');
		$test->shouldReceive('getXsltName')->once()->andReturn('Test XSLT name 2');
		$testsList['Test name - Second'] = $test;

		$this->setPropertyValue($generator, 'templates', $testsList);

		// generate all tests from template
		file_put_contents(__DIR__ . '/file.1.1', 'file content 1.1');
		file_put_contents(__DIR__ . '/file.1.2', 'file content 1.2');
		file_put_contents(__DIR__ . '/file.2.1', 'file content 2.1');
		file_put_contents(__DIR__ . '/file.2.2', 'file content 2.2');
		file_put_contents(__DIR__ . '/file.3.1', 'file content 3.1');
		file_put_contents(__DIR__ . '/file.3.2', 'file content 3.2');

		$testsNumber = $generator->generateAll();
		$this->assertEquals(2, $testsNumber);

		$this->assertFileEquals(__DIR__ . '/XYZ/file.1.1', __DIR__ . '/file.1.1');
		$this->assertFileEquals(__DIR__ . '/XYZ/file.1.2', __DIR__ . '/file.1.2');
		$this->assertFileEquals(__DIR__ . '/XYZ/file.2.1', __DIR__ . '/file.2.1');
		$this->assertFileEquals(__DIR__ . '/XYZ/file.2.2', __DIR__ . '/file.2.2');
		$this->assertFileEquals(__DIR__ . '/ABC/file.3.1', __DIR__ . '/file.3.1');
		$this->assertFileEquals(__DIR__ . '/ABC/file.3.2', __DIR__ . '/file.3.2');

		unlink(__DIR__ . '/file.1.1');
		unlink(__DIR__ . '/file.1.2');
		unlink(__DIR__ . '/file.2.1');
		unlink(__DIR__ . '/file.2.2');
		unlink(__DIR__ . '/file.3.1');
		unlink(__DIR__ . '/file.3.2');
		unlink(__DIR__ . '/XYZ/file.1.1');
		unlink(__DIR__ . '/XYZ/file.1.2');
		unlink(__DIR__ . '/XYZ/file.2.1');
		unlink(__DIR__ . '/XYZ/file.2.2');
		unlink(__DIR__ . '/ABC/file.3.1');
		unlink(__DIR__ . '/ABC/file.3.2');
		rmdir(__DIR__ . '/ABC');
		rmdir(__DIR__ . '/XYZ');
	}


}
