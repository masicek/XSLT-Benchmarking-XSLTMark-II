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
 * MicrotimeTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class MicrotimeTest extends TestCase
{


	public function test()
	{
		$reflection = new \ReflectionClass('\XSLTBenchmarking\Microtime');
		$this->assertFalse($reflection->IsInstantiable());
	}


}
