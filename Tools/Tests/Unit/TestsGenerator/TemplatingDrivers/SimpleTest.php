<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\SimpleTemplatingDriver;

use \Tests\XSLTBenchmark\TestCase;
use \XSLTBenchmark\TestsGenerator\SimpleTemplatingDriver;

require_once ROOT_TOOLS . '/TestsGenerator/TemplatingDrivers/SimpleTemplatingDriver.php';

/**
 * SimpleTemplatingDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmark\TestsGenerator\SimpleTemplatingDriver::generate
 */
class SimpleTemplatingDriverTest extends TestCase
{


	public function testOk()
	{
		$driver = new SimpleTemplatingDriver();
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
