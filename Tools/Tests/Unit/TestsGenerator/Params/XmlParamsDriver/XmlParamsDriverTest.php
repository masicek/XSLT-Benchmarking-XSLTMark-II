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
 * XmlParamsDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::__construct
 */
class XmlParamsDriverTest extends TestCase
{


	public function testOk()
	{
		$driver = new XmlParamsDriver(
			$this->getMock('\XSLTBenchmarking\TestsGenerator\XmlGenerator'),
			__DIR__,
			$this->setDirSep(__DIR__ . '/params.xml')
		);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\XmlParamsDriver', $driver);
	}


	public function testBadTmp()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$driver = new XmlParamsDriver(
			$this->getMock('\XSLTBenchmarking\TestsGenerator\XmlGenerator'),
			$this->setDirSep(__DIR__ . '/unknown'),
			$this->setDirSep(__DIR__ . '/params.xml')
		);
	}


	public function testBadParams()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$driver = new XmlParamsDriver(
			$this->getMock('\XSLTBenchmarking\TestsGenerator\XmlGenerator'),
			__DIR__,
			$this->setDirSep(__DIR__ . '/unknown.xml')
		);
	}


}
