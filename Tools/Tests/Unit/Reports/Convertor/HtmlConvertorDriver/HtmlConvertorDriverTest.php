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
 * HtmlConvertorDriverTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\HtmlConvertorDriver::__construct
 */
class HtmlConvertorDriverTest extends TestCase
{


	public function testWrongTmpDir()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$convertor = new HtmlConvertorDriver('wrong');
	}


	public function testOk()
	{
		$convertor = new HtmlConvertorDriver(__DIR__);
		$this->assertEquals($this->getPropertyValue($convertor, 'tmpDir'), __DIR__);
	}


}
