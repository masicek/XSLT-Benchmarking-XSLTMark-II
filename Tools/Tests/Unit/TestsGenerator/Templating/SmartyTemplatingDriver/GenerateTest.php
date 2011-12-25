<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver;

require_once ROOT_TOOLS . '/TestsGenerator/Templating/SmartyTemplatingDriver.php';

/**
 * SmartyTemplatingDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver::__construct
 * @covers XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver::generate
 * @covers XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver::repareIndent
 */
class GenerateTest extends TestCase
{


	public function testOk()
	{
		$tmpDirPath = __DIR__;
		$driver = new SmartyTemplatingDriver($tmpDirPath);
		$templatePath = $this->setDirSep(__DIR__ . '/template.tpl.xslt');
		$generatedExpectedPath = $this->setDirSep(__DIR__ . '/expectedOutput.xslt');
		$generatedPath = $this->setDirSep(__DIR__ . '/tmpCopy.xslt');

		$this->assertFileNotExists($generatedPath);

		$driver->generate($templatePath, $generatedPath, array(
			'testVariable1' => 'Lorem ipsum 1',
			'testVariable2' => 'Lorem ipsum 2',
		));

		$this->assertFileExists($generatedPath);
		$this->assertFileEquals($generatedExpectedPath, $generatedPath);

		// remove copy of file
		unlink($generatedPath);

		// remove temporary file
		$files = scandir($tmpDirPath);
		foreach ($files as $file)
		{
			if (strpos($file, '.file.template.tpl.xslt.php') !== FALSE)
			{
				unlink($this->setDirSep($tmpDirPath . '/' . $file));
			}
		}
	}


	public function testUnknownVariable()
	{
		$tmpDirPath = __DIR__;
		$driver = new SmartyTemplatingDriver($tmpDirPath);
		$templatePath = $this->setDirSep(__DIR__ . '/template.tpl.xslt');
		$generatedPath = $this->setDirSep(__DIR__ . '/tmpCopy.xslt');

		$this->assertFileNotExists($generatedPath);

		$this->setExpectedException('\XSLTBenchmarking\GenerateTemplateException');
		$driver->generate($templatePath, $generatedPath, array(
			'testVariable1' => 'Lorem ipsum 1',
		));
	}


}
