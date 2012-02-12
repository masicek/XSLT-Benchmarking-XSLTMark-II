<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Convertor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\HtmlConvertorDriver;

require_once ROOT_TOOLS . '/Reports/Convertor/HtmlConvertorDriver.php';

/**
 * ConvertTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\HtmlConvertorDriver::convert
 * @covers \XSLTBenchmarking\Reports\HtmlConvertorDriver::getReport
 * @covers \XSLTBenchmarking\Reports\HtmlConvertorDriver::getAttributes
 */
class ConvertTest extends TestCase
{


	public function testWrongInputFile()
	{
		$convertor = new HtmlConvertorDriver(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generatedFile = $convertor->convert('wrong', __DIR__);
	}


	public function testWrongOutputDir()
	{
		$convertor = new HtmlConvertorDriver(__DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generatedFile = $convertor->convert(__DIR__ . '/FixtureReport/report.xml', 'wrong');
	}


	public function testWrongReportFormat()
	{
		$convertor = new HtmlConvertorDriver(__DIR__);
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$generatedFile = $convertor->convert(__DIR__ . '/FixtureReport/wrong.xml', __DIR__);
	}


	public function testOk()
	{
		$convertor = new HtmlConvertorDriver(__DIR__);
		$generatedFile = $convertor->convert(__DIR__ . '/FixtureReport/report.xml', __DIR__);

		$this->assertEquals($this->setDirSep(__DIR__ . '/report.html'), $generatedFile);

		// remove temporary file
		$files = scandir(__DIR__);
		foreach ($files as $file)
		{
			if (strpos($file, '.file.report.tpl.html.php') !== FALSE)
			{
				unlink($this->setDirSep(__DIR__ . '/' . $file));
			}
		}

		unlink($generatedFile);
	}


}
