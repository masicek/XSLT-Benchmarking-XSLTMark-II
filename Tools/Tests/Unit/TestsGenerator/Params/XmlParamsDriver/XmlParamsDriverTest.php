<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\XmlParamsDriver;

use \Tests\XSLTBenchmark\TestCase;
use \XSLTBenchmark\TestsGenerator\XmlParamsDriver;

require_once ROOT_TOOLS . '/TestsGenerator/Params/XmlParamsDriver.php';

/**
 * XmlParamsDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmark\TestsGenerator\XmlParamsDriver::__construct
 */
class XmlParamsDriverTest extends TestCase
{


	public function test()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'), __DIR__);
		$this->assertInstanceOf('\XSLTBenchmark\TestsGenerator\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmark\TestsGenerator\XmlParamsDriver', $driver);
	}


}
