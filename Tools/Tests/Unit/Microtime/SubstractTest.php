<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Microtime;

require_once ROOT_TOOLS . '/Microtime.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Microtime;

/**
 * SubstractTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Microtime::substract
 */
class SubstractTest extends TestCase
{


	public function test()
	{
		$result = Microtime::substract('1326645761.700327', '1326645761.700327');
		$this->assertSame('0.000000', $result);

		$result = Microtime::substract('1326645761.700327', '1326645761.700326');
		$this->assertSame('0.000001', $result);

		$result = Microtime::substract('1326645761.700327', '1326645761.700328');
		$this->assertSame('-0.000001', $result);

		$result = Microtime::substract('1326645761.700327', '1326645751.700327');
		$this->assertSame('10.000000', $result);

		$result = Microtime::substract('1326645761.700327', '1326645771.700327');
		$this->assertSame('-10.000000', $result);

		$result = Microtime::substract('1326645761.700327', '1326645751.700127');
		$this->assertSame('10.000200', $result);

		$result = Microtime::substract('1326645761.700327', '1326645771.700527');
		$this->assertSame('-10.000200', $result);
	}


}
