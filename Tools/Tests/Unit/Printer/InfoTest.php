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
 * InfoTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Printer::info
 */
class InfoTest extends TestCase
{


	public function testProductionMode()
	{
		$printerPath = $this->setDirSep(ROOT_TOOLS . '/Printer.php');
		$command = 'php -r "require_once \'' . $printerPath . '\'; \XSLTBenchmarking\Printer::$mode = \XSLTBenchmarking\Printer::MODE_PRODUCTION; \XSLTBenchmarking\Printer::info(\'Test info\');"';

		exec($command, $output);

		$this->assertEquals(array('Test info'), $output);
	}


	public function testTestMode()
	{
		Printer::$mode = Printer::MODE_TEST;

		ob_start();
		Printer::info('Test info');
		$output = ob_get_clean();

		$this->assertEquals('Test info' . PHP_EOL, $output);
	}


}
