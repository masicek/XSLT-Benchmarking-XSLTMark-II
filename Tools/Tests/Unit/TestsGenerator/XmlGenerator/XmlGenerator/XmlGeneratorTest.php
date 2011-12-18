<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\XmlGenerator;

use \Tests\XSLTBenchmark\TestCase;
use \XSLTBenchmark\TestsGenerator\XmlGenerator;

require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/XmlGenerator.php';

/**
 * XmlGeneratorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmark\TestsGenerator\XmlGenerator
 */
class XmlGeneratorTest extends TestCase
{


	public function testEasy()
	{
		$params = new XmlGenerator('easy');
		$driver = $this->getPropertyValue($params, 'driver');
		$this->assertInstanceOf('\XSLTBenchmark\TestsGenerator\IXmlGeneratorDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmark\TestsGenerator\EasyXmlGeneratorDriver', $driver);
	}


}
