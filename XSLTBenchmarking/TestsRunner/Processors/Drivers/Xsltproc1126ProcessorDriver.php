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
 * Driver for "xsltproc 1.1.26"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Xsltproc1126ProcessorDriver extends AProcessorDriver
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
				exec('xsltproc --version 2> /dev/null | grep \'libxslt 10126\' | wc -l', $output);
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
				$commandTemplate = '"[PROCESSORS]\libxslt\1.1.26\xsltproc\xsltproc.exe" -o "[OUTPUT]" "[XSLT]" "[INPUT]" 2> "[ERROR]"';
				break;

			case self::OS_LINUX:
				// we assume installing xsltproc 1.1.26
				$commandTemplate = 'xsltproc -o [OUTPUT] [XSLT] [INPUT] 2> [ERROR]';
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
		return 'xsltproc 1.1.26';
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
		return self::KERNEL_LIBXSLT;
	}


}
