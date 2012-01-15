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
 * SumTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Microtime::sum
 */
class SumTest extends TestCase
{


	public function test()
	{
		$result = Microtime::sum(array());
		$this->assertSame('0.000000', $result);

		$result = Microtime::sum(array('1326645761.700327'));
		$this->assertSame('1326645761.700327', $result);

		$result = Microtime::sum(array('1326645761.700327', '0000000000.000001'));
		$this->assertSame('1326645761.700328', $result);

		$result = Microtime::sum(array('1326645761.700327', '-0000000000.000001'));
		$this->assertSame('1326645761.700326', $result);

		$result = Microtime::sum(array('1326645761.700327', '1326645700.700327'));
		$this->assertSame('2653291462.400654', $result);

		$result = Microtime::sum(array('1326645761.700327', '1326645700.700327', '45700.7'));
		$this->assertSame('2653337163.100654', $result);
	}


}
