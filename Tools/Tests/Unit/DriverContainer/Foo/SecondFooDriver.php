<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\DriversContainer;

require_once __DIR__ . '/IFooDriver.php';

/**
 * SecondFooDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SecondFooDriver implements IFooDriver
{


	public function methodOne()
	{
		return 'Second::method1';
	}


	public function methodTwo($arg1, $arg2)
	{
		return 'Second::method2: ' . $arg1 . ' ' . $arg2;
	}


}
