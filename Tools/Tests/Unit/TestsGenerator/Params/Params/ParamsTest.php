<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Params;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Params;

require_once ROOT_TOOLS . '/TestsGenerator/Params/Params.php';
require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/XmlGenerator.php';

/**
 * ParamsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\Params::__construct
 * @covers XSLTBenchmarking\TestsGenerator\Params::setFile
 */
class ParamsTest extends TestCase
{


	public function testBadParams()
	{
		$params = new Params(new \XSLTBenchmarking\TestsGenerator\XmlGenerator(), __DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$params->setFile('./foo.xml');
	}


	public function testBadTmp()
	{
		$params = new Params(new \XSLTBenchmarking\TestsGenerator\XmlGenerator(), $this->setDirSep('./foo-bar'));
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$params->setFile($this->setDirSep(__DIR__ . '/params.xml'));
	}


	public function testXml()
	{
		$params = new Params(new \XSLTBenchmarking\TestsGenerator\XmlGenerator(), __DIR__);
		$params->setFile($this->setDirSep(__DIR__ . '/params.xml'));
		$driver = $this->getPropertyValue($params, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\IParamsDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\XmlParamsDriver', $driver);
	}


}
