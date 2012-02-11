<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Merger;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\Merger;

require_once ROOT_TOOLS . '/Reports/Merger.php';

/**
 * AddReportFileTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\Merger::addReportFile
 */
class AddReportFileTest extends TestCase
{


	public function testAddOne()
	{
		$merger = new Merger();
		$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/base.xml'));
		$reports = $this->getPropertyValue($merger, 'reports');
		$this->assertEquals(1, count($reports));
		$this->assertInstanceOf('\SimpleXMLElement', $reports[0]);
	}


	public function testAddMore()
	{
		$merger = new Merger();
		$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/base.xml'));
		$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/addInput.xml'));
		$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/addTest.xml'));
		$reports = $this->getPropertyValue($merger, 'reports');
		$this->assertEquals(3, count($reports));
		$this->assertInstanceOf('\SimpleXMLElement', $reports[0]);
		$this->assertInstanceOf('\SimpleXMLElement', $reports[1]);
		$this->assertInstanceOf('\SimpleXMLElement', $reports[2]);
	}


	public function testWrongPath()
	{
		$merger = new Merger();
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$merger->addReportFile('unknown');
	}


	public function testWrongXml()
	{
		$merger = new Merger();
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/wrong.xml'));
	}


}
