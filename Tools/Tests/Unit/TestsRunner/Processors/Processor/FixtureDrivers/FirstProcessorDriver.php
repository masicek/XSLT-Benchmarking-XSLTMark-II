<?php

/*
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Processor;

require_once ROOT_TOOLS . '/TestsRunner/Processors/Drivers/AProcessorDriver.php';

/**
 * FirstProcessorDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class FirstProcessorDriver extends \XSLTBenchmarking\TestsRunner\AProcessorDriver
{


	public function getCommandTemplate()
	{
		return '';
	}


	public function getFullName()
	{
		return 'First processor';
	}


	public function getKernel()
	{
		return 'First kernel';
	}


}
