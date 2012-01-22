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
 * SecondProcessorDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SecondProcessorDriver extends \XSLTBenchmarking\TestsRunner\AProcessorDriver
{


	public function getCommandTemplate()
	{
		return 'Second command';
	}


	public function getFullName()
	{
		return 'Second processor';
	}


	public function getKernel()
	{
		return 'Second kernel';
	}


}
