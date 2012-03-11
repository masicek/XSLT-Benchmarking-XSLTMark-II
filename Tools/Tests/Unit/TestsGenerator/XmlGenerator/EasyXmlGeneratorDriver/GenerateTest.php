<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\EasyXmlGeneratorDriver;

require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/EasyXmlGeneratorDriver.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * GenerateTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\EasyXmlGeneratorDriver::generate
 */
class GenerateTest extends TestCase
{


	public function test()
	{
		$generator = new \XSLTBenchmarking\TestsGenerator\EasyXmlGeneratorDriver(__DIR__);
		$outputPath = $this->setDirSep(__DIR__ . '/foo.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/expected.xml');

		$this->assertFileNotExists($outputPath);
		$generator->generate($outputPath, __DIR__, array('first' => 3, 'second' => 2));
		$this->assertFileExists($outputPath);

		$this->assertXmlFileEqualsXmlFile($expectedOutputPath, $outputPath);

		unlink($outputPath);
	}


}
