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
 * Driver for "Sablotron 1.0.3 - command-line"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Sablotron103cmdProcessorDriver extends AProcessorDriver
{


	/**
	 * Return flag, if the driver is available.
	 *
	 * @return bool
	 */
	public function isAvailable()
	{
		switch (PHP_OS)
		{
			case self::OS_WIN:
				return TRUE;
				break;

			case self::OS_LINUX:
				exec('sabcmd --version 2> /dev/null | grep \'sabcmd 1.0.3\' | wc -l', $output);
				if ($output[0] == '0')
				{
					return FALSE;
				}
				else
				{
					return TRUE;
				}
				break;

			default:
				return FALSE;
				break;
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
		switch (PHP_OS)
		{
			case self::OS_WIN:
				$commandTemplate = '"[PROCESSORS]\Sablotron\1.0.3\sabcmd.exe" "file:///[XSLT]" "file:///[INPUT]" "file:///[OUTPUT]" 2> "[ERROR]"';
				break;

			case self::OS_LINUX:
				$commandTemplate = 'sabcmd [XSLT] [INPUT] [OUTPUT] 2> [ERROR]';
				break;
		}

		return $commandTemplate;
	}


	/**
	 * Full name of processor (with version)
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return 'Sablotron 1.0.3 - command-line';
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
		return self::KERNEL_SABLOTRON;
	}


}
