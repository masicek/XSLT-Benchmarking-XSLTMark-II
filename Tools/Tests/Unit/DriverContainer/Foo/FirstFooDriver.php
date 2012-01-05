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
 * FirstFooDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class FirstFooDriver implements IFooDriver
{

	private $param1;

	private $param2;


	public function __construct($param1, $param2)
	{
		$this->param1 = $param1;
		$this->param2 = $param2;
	}


	public function methodOne()
	{
		return 'First::method1 (' . $this->param1 . ')';
	}


	public function methodTwo($arg1, $arg2)
	{
		return 'First::method2: (' . $this->param2 . ') ' . $arg1 . ' ' . $arg2;
	}


}
