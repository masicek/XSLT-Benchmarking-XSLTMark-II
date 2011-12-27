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
 * XmlParamsDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::__construct
 */
class XmlParamsDriverTest extends TestCase
{


	public function test()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/params.xml'));
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\XmlParamsDriver', $driver);
	}


}
