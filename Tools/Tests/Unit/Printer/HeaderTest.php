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
 * HeaderTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Printer::header
 */
class HeaderTest extends TestCase
{


	public function testProductionMode()
	{
		$printerPath = $this->setDirSep(ROOT_TOOLS . '/Printer.php');
		$command = 'php -r "require_once \'' . $printerPath . '\'; \XSLTBenchmarking\Printer::$mode = \XSLTBenchmarking\Printer::MODE_PRODUCTION; \XSLTBenchmarking\Printer::header(\'Test header\');"';
		if (PHP_OS == 'Linux')
		{
			$command = str_replace('$', '\\$', $command);
		}

		exec($command, $output);

		$this->assertEquals(array('Test header:', '------------'), $output);
	}


	public function testTestMode()
	{
		Printer::$mode = Printer::MODE_TEST;

		ob_start();
		Printer::header('Test header');
		$output = ob_get_clean();

		$this->assertEquals('Test header:' . PHP_EOL . '------------' . PHP_EOL, $output);
	}


}
