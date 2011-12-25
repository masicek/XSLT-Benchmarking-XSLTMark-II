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


	public function testGetTemplateName()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertEquals('Modify element', $driver->getTemplateName());
	}


	public function testGetTemplatePath()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertEquals($this->setDirSep(__DIR__ . '/test.tpl.xslt'), $driver->getTemplatePath());
	}


	public function testGetTemplatingType()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertEquals('smarty', $driver->getTemplatingType());
	}


	public function testGetTestsNames()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertEquals(array('Rename', 'Remove'), $driver->getTestsNames());
	}


	public function testGetTestFilesPaths()
	{
		$tmpDir = $this->setDirSep(__DIR__ . '/tmp');
		mkdir($tmpDir);
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), $tmpDir);

		$this->assertFileNotExists($this->setDirSep($tmpDir . '/manyElements.xml'));
		$this->assertFileNotExists($this->setDirSep($tmpDir . '/manyNewElements.xml'));

		$this->assertEquals(
			array(
				$this->setDirSep(__DIR__ . '/oneElement.xml') => $this->setDirSep(__DIR__ . '/oneNewElement.xml'),
				$this->setDirSep(__DIR__ . '/twoElements.xml') => $this->setDirSep(__DIR__ . '/twoNewElements.xml'),
				$this->setDirSep($tmpDir . '/manyElements.xml') => $this->setDirSep($tmpDir . '/manyNewElements.xml'),
			),
			$driver->getTestFilesPaths('Rename')
		);

		$this->assertFileExists($this->setDirSep($tmpDir . '/manyElements.xml'));
		$this->assertFileExists($this->setDirSep($tmpDir . '/manyNewElements.xml'));

		$this->assertEquals(
			array(
				$this->setDirSep(__DIR__ . '/oneElement.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
				$this->setDirSep(__DIR__ . '/twoElements.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
				$this->setDirSep($tmpDir . '/manyElements.xml') => $this->setDirSep(__DIR__ . '/zeroElement.xml'),
			),
			$driver->getTestFilesPaths('Remove')
		);


		// remove all files in tmp dir
		unlink($this->setDirSep($tmpDir . '/manyElements.xml'));
		unlink($this->setDirSep($tmpDir . '/manyNewElements.xml'));
		rmdir($tmpDir);
	}


	public function testGetTestSettings()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);

		$this->assertEquals(
			array(
				'action' => 'rename',
				'newName' => 'newTestName',
			),
			$driver->getTestSettings('Rename')
		);

		$this->assertEquals(
			array(
				'action' => 'remove',
			),
			$driver->getTestSettings('Remove')
		);
	}


	public function testGetTestParamsFileName()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);

		$this->assertEquals('myParams.xml', $driver->getTestParamsFileName('Rename'));
		$this->assertNull($driver->getTestParamsFileName('Remove'));
	}


}
