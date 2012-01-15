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
 * PrinterTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\Printer::__construct
 */
class PrinterTest extends TestCase
{


	public function testOk()
	{
		$printer = new Printer(__DIR__, array('Lorem', 'ipsum'), 111);
		$this->assertEquals(__DIR__, $this->getPropertyValue($printer, 'reportsDir'));
		$this->assertEquals(array('Lorem', 'ipsum'), $this->getPropertyValue($printer, 'processors'));
		$this->assertEquals(111, $this->getPropertyValue($printer, 'repeating'));
	}


	public function testBadReportsDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$printer = new Printer('unknown', array(), 111);
	}


}
