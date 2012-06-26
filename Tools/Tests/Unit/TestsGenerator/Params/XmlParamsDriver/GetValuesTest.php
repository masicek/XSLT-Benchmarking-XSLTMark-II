<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\XmlParamsDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\XmlParamsDriver;

require_once ROOT_TOOLS . '/TestsGenerator/Params/XmlParamsDriver.php';


/**
 * GetValuesTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTemplateName
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTemplatePath
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTemplatingType
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTestsNames
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTestFilesPaths
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTestSettings
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getTestParamsFileName
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::getAllFilesPaths
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::createAllFilesPaths
 */
class GetValuesTest extends TestCase
{

	private $driver;

	public function setUp()
	{
		$this->driver = new XmlParamsDriver(
			$this->getMock('\XSLTBenchmarking\TestsGenerator\XmlGenerator'),
			__DIR__,
			$this->setDirSep(__DIR__ . '/params.xml')
		);
	}

	public function testGetTemplateName()
	{
		$this->assertEquals('Modify element', $this->driver->getTemplateName());
	}


	public function testGetTemplatePath()
	{
		$this->assertEquals($this->setDirSep(__DIR__ . '/test.tpl.xslt'), $this->driver->getTemplatePath());
	}


	public function testGetTemplatingType()
	{
		$this->assertEquals('smarty', $this->driver->getTemplatingType());
	}


	public function testGetTestsNames()
	{
		$this->assertEquals(array('Rename', 'Remove'), $this->driver->getTestsNames());
	}


	public function testGetTestFilesPaths()
	{
		$generator = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\XmlGenerator()');
		$generator->shouldReceive('setDriver')->twice()->with('easy');
		$generator->shouldReceive('generate')->once()->with(
			$this->setDirSep(__DIR__ . '/modify-element/manyElements.xml'),
			__DIR__,
			array('testName' => 20, 'testName2' => 3)
		);
		$generator->shouldReceive('generate')->once()->with(
			$this->setDirSep(__DIR__ . '/modify-element/manyNewElements.xml'),
			__DIR__,
			array('testNewName' => 20, 'testName2' => 3)
		);

		$this->setPropertyValue($this->driver, 'xmlGenerator', $generator);


		$this->assertEquals(
			array(
				$this->setDirSep(__DIR__ . '/oneElement.xml') => $this->setDirSep(__DIR__ . '/oneNewElement.xml'),
				$this->setDirSep(__DIR__ . '/twoElements.xml') => $this->setDirSep(__DIR__ . '/twoNewElements.xml'),
				$this->setDirSep(__DIR__ . '/modify-element/manyElements.xml') => $this->setDirSep(__DIR__ . '/modify-element/manyNewElements.xml'),
			),
			$this->driver->getTestFilesPaths('Rename')
		);

		$this->assertEquals(
			array(
				$this->setDirSep(__DIR__ . '/oneElement.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
				$this->setDirSep(__DIR__ . '/twoElements.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
				$this->setDirSep(__DIR__ . '/modify-element/manyElements.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
			),
			$this->driver->getTestFilesPaths('Remove')
		);

		rmdir($this->setDirSep(__DIR__ . '/modify-element'));
	}


	public function testGetTestSettings()
	{
		$this->assertEquals(
			array(
				'action' => 'rename',
				'newName' => 'newTestName',
			),
			$this->driver->getTestSettings('Rename')
		);

		$this->assertEquals(
			array(
				'action' => 'remove',
			),
			$this->driver->getTestSettings('Remove')
		);
	}


	public function testGetTestParamsFileName()
	{
		$this->assertEquals('myParams.xml', $this->driver->getTestParamsFileName('Rename'));
		$this->assertNull($this->driver->getTestParamsFileName('Remove'));
	}


}
