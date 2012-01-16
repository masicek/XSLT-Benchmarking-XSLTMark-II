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
 * HumanReadableTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Microtime::humanReadable
 */
class HumanReadableTest extends TestCase
{


	public function test()
	{
		$result = Microtime::humanReadable('6.700327');
		$this->assertSame('00:00:06.700327', $result);

		$result = Microtime::humanReadable('43801.700327');
		$this->assertSame('12:10:01.700327', $result);

		$result = Microtime::humanReadable('216601.700327');
		$this->assertSame('2days 12:10:01.700327', $result);

		$result = Microtime::humanReadable('1326645761.700327');
		$this->assertSame('15354days 16:42:41.700327', $result);
	}


}
