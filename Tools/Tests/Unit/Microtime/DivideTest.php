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
 * DivideTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Microtime::divide
 */
class DivideTest extends TestCase
{


	public function test()
	{
		$result = Microtime::divide('1326645761.700327', '1326645761.700327');
		$this->assertSame('1.000000', $result);

		$result = Microtime::divide('1326645761.700327', '2.700327');
		$this->assertSame('491290781.338825', $result);

		$result = Microtime::divide('1326645761.700327', '0.700327');
		$this->assertSame('1894323311.396429', $result);
	}


}
