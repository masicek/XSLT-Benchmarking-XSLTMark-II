<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\XmlParamsDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\XmlParamsDriver;

require_once ROOT_TOOLS . '/TestsRunner/Params/XmlParamsDriver.php';

/**
 * GetValuesTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::getName
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::getTemplatePath
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::getCouplesPaths
 */
class GetValuesTest extends TestCase
{


	public function testGetName()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'));
		$this->assertEquals('Test name', $driver->getName());
	}


	public function testGetTemplatePath()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'));
		$this->assertEquals($this->setDirSep(__DIR__ . '/test.xslt'), $driver->getTemplatePath());
	}


	public function testGetCouplesPaths()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'));
		$this->assertEquals(array(
				$this->setDirSep(__DIR__ . '/firstIn.xml') => $this->setDirSep(__DIR__ . '/firstOut.xml'),
				$this->setDirSep(__DIR__ . '/secondIn.xml') => $this->setDirSep(__DIR__ . '/secondOut.xml'),
			),
			$driver->getCouplesPaths()
		);
	}


}
