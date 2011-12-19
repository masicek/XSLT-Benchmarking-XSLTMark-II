<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\SimpleTemplatingDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\SimpleTemplatingDriver;

require_once ROOT_TOOLS . '/TestsGenerator/Templating/SimpleTemplatingDriver.php';

/**
 * SimpleTemplatingDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\SimpleTemplatingDriver::__construct
 * @covers XSLTBenchmarking\TestsGenerator\SimpleTemplatingDriver::generate
 */
class GenerateTest extends TestCase
{


	public function test()
	{
		$driver = new SimpleTemplatingDriver(NULL);
		$templatePath = __FILE__;
		$copyPath = $this->setDirSep(__DIR__ . '/tmpCopy.php');

		$this->assertFileNotExists($copyPath);

		$driver->generate($templatePath, $copyPath);

		$this->assertFileExists($copyPath);
		$this->assertFileEquals($templatePath, $copyPath);

		// remove copy of file
		unlink($copyPath);
	}


}
