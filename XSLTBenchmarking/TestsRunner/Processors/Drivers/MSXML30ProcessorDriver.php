<?php

/*
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/AProcessorDriver.php';
// @codeCoverageIgnoreEnd

/**
 * Driver for "MSXML 3.0"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class MSXML30ProcessorDriver extends AProcessorDriver
{


	/**
	 * Return flag, if the driver is available.
	 *
	 * @return bool
	 */
	public function isAvailable()
	{
		if (PHP_OS == self::OS_WIN)
		{
			exec('cscript //nologo "' . __DIR__ . '\MSXMLAvailability.js" "3.0"', $output);

			if (count($output) == 0)
			{
				return FALSE;
			}
			else
			{
				$output = $output[0];
				if ($output == 'available')
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Return template of command
	 *
	 * Templates substitutions:
	 * [XSLT] = path of XSLT template for transformation
	 * [INPUT] = path of input XML file
	 * [OUTPUT] = path of generated output XML file
	 * [ERROR] = path of file for eventual generated error message
	 * [LIBS] = path of directory containing XSLT processors (libraries, command-line program etc.)
	 *
	 * @return string
	 */
	public function getCommandTemplate()
	{
		$commandTemplate = 'cscript //nologo "[PROCESSORS]\MSXML\msxml.js" "[INPUT]" "[XSLT]" "[OUTPUT]" "[ERROR]" "3.0"';
		return $commandTemplate;
	}


	/**
	 * Full name of processor (with version)
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return 'MSXML 3.0';
	}


	/**
	 * Return name of processor kernel.
	 * Available kernels are const of this class with prefix "KERNEL_"
	 *
	 * Examples:
	 * Saxon 6.5.5 -> Saxon
	 * xsltproc -> libxslt
	 *
	 * @return string
	 */
	public function getKernel()
	{
		return self::KERNEL_MSXML;
	}


}
