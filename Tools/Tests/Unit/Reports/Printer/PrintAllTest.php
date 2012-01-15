<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Printer;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\Printer;

require_once ROOT_TOOLS . '/Reports/Printer.php';

/**
 * PrintAllTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\Printer::printAll
 */
class PrintAllTest extends TestCase
{


	public function test()
	{
		mkdir(__DIR__ . '/reports');

		$processors = array(
			'processor1' => array('fullName' => 'full name 1', 'link' => 'link 1', 'version' => 'version 1'),
			'processor2' => array('fullName' => 'full name 2', 'link' => 'link 2', 'version' => 'version 2'),
			'processor3' => array('fullName' => 'full name 3', 'link' => 'link 3', 'version' => 'version 3'),
		);
		$printer = new Printer(__DIR__ . '/reports', $processors, 111);

		// report 1
		$report1 = \Mockery::mock('\XSLTBenchmarking\Reports\Report');
		$report1->shouldReceive('getTestName')->andReturn('First test');
		$report1->shouldReceive('getTemplatePath')->andReturn('template 1');
		$report1->shouldReceive('getProcessors')->andReturn(array('processor1', 'processor2', 'processor3'));
		$report1->shouldReceive('getInputs')->with('processor1')->andReturn(array(
			array('input' => 'input 1', 'expectedOutput' => 'expected 1', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '123.456', 'avgTime' => '444.555'),
			array('input' => 'input 2', 'expectedOutput' => 'expected 2', 'success' => 'Error 1', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 3', 'expectedOutput' => 'expected 3', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '333.666', 'avgTime' => '1.2'),
			array('input' => 'input 4', 'expectedOutput' => 'expected 1', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '111.222', 'avgTime' => '333.444'),
		));
		$report1->shouldReceive('getInputs')->with('processor2')->andReturn(array(
			array('input' => 'input 1', 'expectedOutput' => 'expected 1', 'success' => 'Error 2.1', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 2', 'expectedOutput' => 'expected 1', 'success' => 'Error 2.2', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 3', 'expectedOutput' => 'expected 1', 'success' => 'Error 2.3', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 4', 'expectedOutput' => 'expected 1', 'success' => 'Error 2.4', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
		));
		$report1->shouldReceive('getInputs')->with('processor3')->andReturn(array(
			array('input' => 'input 1', 'expectedOutput' => 'expected 1', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '101', 'avgTime' => '201'),
			array('input' => 'input 2', 'expectedOutput' => 'expected 2', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '102', 'avgTime' => '202'),
			array('input' => 'input 3', 'expectedOutput' => 'expected 3', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '103', 'avgTime' => '203'),
			array('input' => 'input 4', 'expectedOutput' => 'expected 4', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '104', 'avgTime' => '204'),
		));

		// report 2
		$report2 = \Mockery::mock('\XSLTBenchmarking\Reports\Report');
		$report2->shouldReceive('getTestName')->andReturn('Second test');
		$report2->shouldReceive('getTemplatePath')->andReturn('template 2');
		$report2->shouldReceive('getProcessors')->andReturn(array('processor1', 'processor2', 'processor3'));
		$report2->shouldReceive('getInputs')->with('processor1')->andReturn(array(
			array('input' => 'input 10', 'expectedOutput' => 'expected 10', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '9123.456', 'avgTime' => '9444.555'),
			array('input' => 'input 20', 'expectedOutput' => 'expected 20', 'success' => 'Error 10', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 30', 'expectedOutput' => 'expected 30', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '9333.666', 'avgTime' => '91.2'),
			array('input' => 'input 40', 'expectedOutput' => 'expected 10', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '9111.222', 'avgTime' => '9333.444'),
		));
		$report2->shouldReceive('getInputs')->with('processor2')->andReturn(array(
			array('input' => 'input 10', 'expectedOutput' => 'expected 10', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '1001', 'avgTime' => '2001'),
			array('input' => 'input 20', 'expectedOutput' => 'expected 20', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '1002', 'avgTime' => '2002'),
			array('input' => 'input 30', 'expectedOutput' => 'expected 30', 'success' => 'OK', 'corretness' => TRUE, 'sumTime' => '1003', 'avgTime' => '2003'),
			array('input' => 'input 40', 'expectedOutput' => 'expected 40', 'success' => 'OK', 'corretness' => FALSE, 'sumTime' => '1004', 'avgTime' => '2004'),
		));
		$report2->shouldReceive('getInputs')->with('processor3')->andReturn(array(
			array('input' => 'input 10', 'expectedOutput' => 'expected 10', 'success' => 'Error 3.1', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 20', 'expectedOutput' => 'expected 10', 'success' => 'Error 3.2', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 30', 'expectedOutput' => 'expected 10', 'success' => 'Error 3.3', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
			array('input' => 'input 40', 'expectedOutput' => 'expected 10', 'success' => 'Error 3.4', 'corretness' => '', 'sumTime' => '', 'avgTime' => ''),
		));

		$this->setPropertyValue($printer, 'reports', array($report1, $report2));

		$reportFilePath1 = $printer->printAll();
		sleep(1);
		$reportFilePath2 = $printer->printAll();

		$this->assertNotEquals($reportFilePath1, $reportFilePath2);

		$this->assertXmlFileEqualsXmlFile(__DIR__ . '/expectedReport.xml', $reportFilePath1);
		$this->assertXmlFileEqualsXmlFile(__DIR__ . '/expectedReport.xml', $reportFilePath2);

		unlink($reportFilePath1);
		unlink($reportFilePath2);
		rmdir(__DIR__ . '/reports');
	}


}
