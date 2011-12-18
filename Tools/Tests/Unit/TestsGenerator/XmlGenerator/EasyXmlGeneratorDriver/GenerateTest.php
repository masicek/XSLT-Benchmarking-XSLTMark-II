<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\EasyXmlGeneratorDriver;

require_once ROOT . '/TestsGenerator/XmlGenerator/EasyXmlGeneratorDriver.php';

use \Tests\XSLTBenchmark\TestCase;

/**
 * GenerateTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmark\TestsGenerator\EasyXmlGeneratorDriver::generate
 */
class GenerateTest extends TestCase
{


	public function test()
	{
		$generator = new \XSLTBenchmark\TestsGenerator\EasyXmlGeneratorDriver();
		$outputPath = $this->setDirSep(__DIR__ . '/foo.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/expected.xml');

		$this->assertFileNotExists($outputPath);
		$generator->generate($outputPath, array('first' => 3, 'second' => 2));
		$this->assertFileExists($outputPath);

		$this->assertXmlFileEqualsXmlFile($expectedOutputPath, $outputPath);

		unlink($outputPath);
	}


}
