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
 * AddReportTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\Printer::addReport
 */
class AddReportTest extends TestCase
{


	public function test()
	{
		$printer = new Printer(__DIR__, array('Lorem', 'ipsum'), 111);
		$report1 = $this->getMock('\XSLTBenchmarking\Reports\Report', array(), array(), '', FALSE);
		$report2 = $this->getMock('\XSLTBenchmarking\Reports\Report', array(), array(), '', FALSE);
		$report3 = $this->getMock('\XSLTBenchmarking\Reports\Report', array(), array(), '', FALSE);

		$reports = $this->getPropertyValue($printer, 'reports');
		$this->assertEquals(array(), $reports);

		$printer->addReport($report1);
		$reports = $this->getPropertyValue($printer, 'reports');
		$this->assertEquals(array($report1), $reports);

		$printer->addReport($report2);
		$reports = $this->getPropertyValue($printer, 'reports');
		$this->assertEquals(array($report1, $report2), $reports);

		$printer->addReport($report3);
		$reports = $this->getPropertyValue($printer, 'reports');
		$this->assertEquals(array($report1, $report2, $report3), $reports);
	}


}
