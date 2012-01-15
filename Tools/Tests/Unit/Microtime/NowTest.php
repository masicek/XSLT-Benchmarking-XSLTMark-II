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
 * NowTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Microtime::now
 */
class NowTest extends TestCase
{


	public function test()
	{
		list($usecBefore, $secBefore) = explode(' ', microtime());
		$now = Microtime::now();
		list($usecAfter, $secAfter) = explode(' ', microtime());
		$this->assertRegExp('/[0-9]+[.][0-9]+/', $now);

		list($sec, $usec) = explode('.', $now);

		$this->assertLessThanOrEqual((int)$secBefore, (int)$sec);
		$this->assertLessThanOrEqual((int)$sec, (int)$secAfter);
	}


}
