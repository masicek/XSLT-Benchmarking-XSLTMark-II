<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\SmartyTemplatingDriver;

use \Tests\XSLTBenchmark\TestCase;
use \XSLTBenchmark\TestsGenerator\SmartyTemplatingDriver;

require_once ROOT_TOOLS . '/TestsGenerator/TemplatingDrivers/SmartyTemplatingDriver.php';

/**
 * SmartyTemplatingDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmark\TestsGenerator\SmartyTemplatingDriver::__construct
 * @covers XSLTBenchmark\TestsGenerator\SmartyTemplatingDriver::generate
 */
class SmartyTemplatingDriverTest extends TestCase
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
			if (strpos($file, '.file.template.tpl.xslt') !== FALSE)
			{
				unlink($this->setDirSep(__DIR__ . '/' . $file));
			}
		}
	}


}
