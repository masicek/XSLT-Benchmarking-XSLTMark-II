<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Report;

require_once ROOT_TOOLS . '/Reports/Report.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\Report;

/**
 * ReportTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class ReportTest extends TestCase
{


	/**
	 * @covers \XSLTBenchmarking\Reports\Report::__construct
	 * @covers \XSLTBenchmarking\Reports\Report::getTestName
	 * @covers \XSLTBenchmarking\Reports\Report::getTemplatePath
	 */
	public function testNameAndTemplate()
	{
		$report = new Report('Test name', 'Template path');
		$this->assertEquals('Test name', $report->getTestName());
		$this->assertEquals('Template path', $report->getTemplatePath());
	}


	/**
	 * @covers \XSLTBenchmarking\Reports\Report::addRecord
	 * @covers \XSLTBenchmarking\Reports\Report::getProcessors
	 * @covers \XSLTBenchmarking\Reports\Report::getInputs
	 */
	public function testOneRecord()
	{
		$report = new Report('Test name', 'Template path');
		$report->addRecord(
			'processor1',
			'input path 1',
			'expected output path 1',
			'OK',
			TRUE,
			array('123.456'),
			111
		);

		$this->assertEquals(array('processor1'), $report->getProcessors());

		$this->assertEquals(array(
				array(
					'input' => 'input path 1',
					'expectedOutput' => 'expected output path 1',
					'success' => 'OK',
					'correctness' => TRUE,
					'sumTime' => '123.456',
					'avgTime' => '123.456',
					'repeating' => '111',
				),
			),
			$report->getInputs('processor1')
		);
	}


	/**
	 * @covers \XSLTBenchmarking\Reports\Report::addRecord
	 * @covers \XSLTBenchmarking\Reports\Report::getProcessors
	 * @covers \XSLTBenchmarking\Reports\Report::getInputs
	 */
	public function testMoreRecords()
	{
		$report = new Report('Test name', 'Template path');
		$report->addRecord(
			'processor1',
			'input path 1',
			'expected output path 1',
			'OK',
			TRUE,
			array('123.456'),
			111
		);
		$report->addRecord(
			'processor1',
			'input path 2',
			'expected output path 2',
			'Error 1',
			FALSE,
			array(),
			222
		);
		$report->addRecord(
			'processor2',
			'input path 1',
			'expected output path 1',
			'OK',
			TRUE,
			array('555.666', '444.555', '666.777'),
			333
		);

		$this->assertEquals(array('processor1', 'processor2'), $report->getProcessors());

		$this->assertEquals(array(
				array(
					'input' => 'input path 1',
					'expectedOutput' => 'expected output path 1',
					'success' => 'OK',
					'correctness' => TRUE,
					'sumTime' => '123.456',
					'avgTime' => '123.456',
					'repeating' => '111',
				),
				array(
					'input' => 'input path 2',
					'expectedOutput' => 'expected output path 2',
					'success' => 'Error 1',
					'correctness' => FALSE,
					'sumTime' => '',
					'avgTime' => '',
					'repeating' => '222',
				),
			),
			$report->getInputs('processor1')
		);

		$this->assertEquals(array(
				array(
					'input' => 'input path 1',
					'expectedOutput' => 'expected output path 1',
					'success' => 'OK',
					'correctness' => TRUE,
					'sumTime' => '1666.998000',
					'avgTime' => '555.666',
					'repeating' => '333',
				),
			),
			$report->getInputs('processor2')
		);
	}


	/**
	 * @covers \XSLTBenchmarking\Reports\Report::getInputs
	 */
	public function testUnknownProcessor()
	{
		$report = new Report('Test name', 'Template path');
		$report->addRecord(
			'processor1',
			'input path 1',
			'expected output path 1',
			'OK',
			TRUE,
			array('123.456'),
			111
		);

		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$report->getInputs('unknown');
	}


}
