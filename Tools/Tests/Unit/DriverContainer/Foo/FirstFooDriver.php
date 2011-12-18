<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\DriversContainer;

require_once __DIR__ . '/IFooDriver.php';

/**
 * FirstFooDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class FirstFooDriver implements IFooDriver
{


	public function methodOne()
	{
		return 'First::method1';
	}


	public function methodTwo($arg1, $arg2)
	{
		return 'First::method2: ' . $arg1 . ' ' . $arg2;
	}


}
