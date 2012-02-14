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
 * GenerateTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::__construct
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::generate
 */
class GenerateTest extends TestCase
{


	public function test()
	{
		$expectedFile = $this->setDirSep(__DIR__ . '/expectedGeneratedParams.xml');
		$generatedFile = $this->setDirSep(__DIR__ . '/generatedParams.xml');
		$driver = new XmlParamsDriver($generatedFile, FALSE);
		$this->assertFileNotExists($generatedFile);
		$driver->generate('Test name', 'Test template name', array('input1' => 'output1', 'input2' => 'output2'));
		$this->assertXmlFileEqualsXmlFile($expectedFile, $generatedFile);
		unlink($generatedFile);

	}


}
