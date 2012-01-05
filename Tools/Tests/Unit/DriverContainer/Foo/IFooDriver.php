<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\DriversContainer;

/**
 * IFooDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IFooDriver
{


	public function __construct($param1, $param2);


	public function methodOne();


	public function methodTwo($arg1, $arg2);


}
