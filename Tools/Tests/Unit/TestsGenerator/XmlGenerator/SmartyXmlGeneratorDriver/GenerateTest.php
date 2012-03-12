<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\SmartyXmlGeneratorDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\SmartyXmlGeneratorDriver;

require_once ROOT_TOOLS . '/TestsGenerator/XmlGenerator/SmartyXmlGeneratorDriver.php';

/**
 * SmartyXmlGeneratorDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\SmartyXmlGeneratorDriver::__construct
 * @covers XSLTBenchmarking\TestsGenerator\SmartyXmlGeneratorDriver::generate
 */
class GenerateTest extends TestCase
{


	public function testOk()
	{
		$tmpDirPath = __DIR__;
		$driver = new SmartyXmlGeneratorDriver($tmpDirPath);
		$generatedExpectedPath = $this->setDirSep(__DIR__ . '/expectedOutput.xml');
		$generatedPath = $this->setDirSep(__DIR__ . '/tmpCopy.xml');

		$this->assertFileNotExists($generatedPath);

		$driver->generate($generatedPath, __DIR__, array(
			'template' => 'template.tpl.xml',
			'testVariable1' => 'Lorem ipsum 1',
			'testVariable2' => 'Lorem ipsum 2',
		));

		$this->assertFileExists($generatedPath);

		$generatedExpected = str_replace("\r\n", PHP_EOL, file_get_contents($generatedExpectedPath));
		$generated = file_get_contents($generatedPath);
		$this->assertEquals($generatedExpected, $generated);

		// remove copy of file
		unlink($generatedPath);

		// remove temporary file
		$files = scandir($tmpDirPath);
		foreach ($files as $file)
		{
			if (strpos($file, '.file.template.tpl.xml.php') !== FALSE)
			{
				unlink($this->setDirSep($tmpDirPath . '/' . $file));
			}
		}
	}


	public function testBadTemapltePath()
	{
		$driver = new SmartyXmlGeneratorDriver(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$driver->generate('output/path', __DIR__, array('template' => 'foo.php'));
	}


	public function testBadTmpDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$driver = new SmartyXmlGeneratorDriver($this->setDirSep(__DIR__ . '/unknownDir'));
	}


	public function testUnknownVariable()
	{
		$this->markTestSkippedCondition();

		$tmpDirPath = __DIR__;
		$driver = new SmartyXmlGeneratorDriver($tmpDirPath);
		$generatedPath = $this->setDirSep(__DIR__ . '/tmpCopy.xml');

		$this->assertFileNotExists($generatedPath);

		$this->setExpectedException('\XSLTBenchmarking\GenerateXmlException');
		$driver->generate($generatedPath, __DIR__, array(
			'template' => 'template.tpl.xml',
			'testVariable1' => 'Lorem ipsum 1',
		));
	}


}
