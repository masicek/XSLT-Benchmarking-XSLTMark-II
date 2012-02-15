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
 * NotAvailableProcessorDriver
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class NotAvailableProcessorDriver extends \XSLTBenchmarking\TestsRunner\AProcessorDriver
{


	public function isAvailable()
	{
		return FALSE;
	}


	public function getCommandTemplate()
	{
		return '';
	}


	public function getEmptyCommandTemplate()
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
