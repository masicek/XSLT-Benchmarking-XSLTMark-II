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
 * Driver for "XT 20051206"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class XT20051206ProcessorDriver extends AProcessorDriver
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
			return TRUE;
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
		switch (PHP_OS)
		{
			case self::OS_WIN:
				$prefix = '"[LIBS]\Java\1.6.0_29\java.exe"';
				break;

//			case self::OS_LINUX:
//				$prefix = '[LIBS]/Java/??????/java';
//				break;

		}

		$commandTemplate = $prefix . ' -cp "[PROCESSORS]\XT\20051206\xt20051206.jar";"[PROCESSORS]\XT\20051206\xp.jar";"[PROCESSORS]\XT\20051206\sax2r2.jar" com.jclark.xsl.sax.Driver "[INPUT]" "[XSLT]" "[OUTPUT]" 2> "[ERROR]"';
		$commandTemplate = str_replace('\\', DIRECTORY_SEPARATOR, $commandTemplate);
		return $commandTemplate;
	}


	/**
	 * Full name of processor (with version)
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return 'XT 20051206';
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
		return self::KERNEL_XT;
	}


}
