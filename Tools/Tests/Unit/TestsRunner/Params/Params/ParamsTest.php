<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Params;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Params;

require_once ROOT_TOOLS . '/TestsRunner/Params/Params.php';

/**
 * ParamsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsRunner\Params::__construct
 * @covers XSLTBenchmarking\TestsRunner\Params::setFile
 */
class ParamsTest extends TestCase
{


	public function testXml()
	{
		$params = new Params();
		$params->setFile($this->setDirSep(__DIR__ . '/params.xml'));
		$driver = $this->getPropertyValue($params, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\XmlParamsDriver', $driver);
	}


}
