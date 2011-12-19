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
 * @covers \XSLTBenchmarking\TestsGenerator\XmlParamsDriver::__construct
 */
class XmlParamsDriverTest extends TestCase
{


	public function test()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\XmlParamsDriver', $driver);
	}


}
