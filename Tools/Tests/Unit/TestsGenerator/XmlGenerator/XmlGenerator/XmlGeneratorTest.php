<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\XmlGenerator;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\XmlGenerator;

require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/XmlGenerator.php';

/**
 * XmlGeneratorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\XmlGenerator
 */
class XmlGeneratorTest extends TestCase
{


	public function testEasy()
	{
		$generator = new XmlGenerator();
		$generator->setDriver('easy');
		$driver = $this->getPropertyValue($generator, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\IXmlGeneratorDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\EasyXmlGeneratorDriver', $driver);
	}


}
