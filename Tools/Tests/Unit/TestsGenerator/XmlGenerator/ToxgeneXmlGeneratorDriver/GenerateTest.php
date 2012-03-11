<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\ToxgeneXmlGeneratorDriver;

require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/ToxgeneXmlGeneratorDriver.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * GenerateTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\ToxgeneXmlGeneratorDriver::generate
 */
class GenerateTest extends TestCase
{


	public function test()
	{
		$tmpDir = $this->setDirSep(__DIR__ . '/tmp/');
		mkdir($tmpDir);
		$generator = new \XSLTBenchmarking\TestsGenerator\ToxgeneXmlGeneratorDriver($tmpDir);
		$outputPath = $this->setDirSep(__DIR__ . '/tmp/foo.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/expected.xml');

		$this->assertFileNotExists($outputPath);
		$generator->generate($outputPath, __DIR__, array('seed' => 111, 'template' => 'movies.tsl'));
		$this->assertFileExists($outputPath);

		$this->assertXmlFileEqualsXmlFile($expectedOutputPath, $outputPath);

		unlink($outputPath);
		rmdir($tmpDir);
	}


}
