<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Convertor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\Convertor;

require_once ROOT_TOOLS . '/Reports/Convertor/Convertor.php';

/**
 * ConvertorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class ConvertorTest extends TestCase
{


	public function testHtml()
	{
		$convertor = new Convertor(__DIR__);
		$convertor->setDriver('html');
		$driver = $this->getPropertyValue($convertor, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\Reports\IConvertorDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\Reports\HtmlConvertorDriver', $driver);
	}


}
