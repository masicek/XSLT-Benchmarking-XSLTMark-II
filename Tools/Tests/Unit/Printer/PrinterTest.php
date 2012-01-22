<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Printer;

require_once ROOT_TOOLS . '/Printer.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Printer;

/**
 * PrinterTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class PrinterTest extends TestCase
{


	public function testStatic()
	{
		$reflection = new \ReflectionClass('\XSLTBenchmarking\Printer');
		$this->assertFalse($reflection->IsInstantiable());
	}


}
