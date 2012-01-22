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
 * Driver for "libxslt 1.1.23 - command-line"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Libxslt1123cmdProcessorDriver extends AProcessorDriver
{


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
				$commandTemplate = '[LIBS]\libxslt\1.1.23\xsltproc\xsltproc.exe -o "[OUTPUT]" "[XSLT]" "[INPUT]" 2> "[ERROR]"';
				break;

//			case self::OS_LINUX:
//				$commandTemplate = '[LIBS]/libxslt/1.1.23/xsltproc -o [OUTPUT] [XSLT] [INPUT] 2> [ERROR]';
//				break;

			default:// @codeCoverageIgnoreStart
				throw new \XSLTBenchmarking\UnsupportedOSException();
				break;
		}// @codeCoverageIgnoreEnd

		return $commandTemplate;
	}


	/**
	 * Full name of processor (with version)
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return 'libxslt 1.1.23 - command-line - xsltproc';
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
