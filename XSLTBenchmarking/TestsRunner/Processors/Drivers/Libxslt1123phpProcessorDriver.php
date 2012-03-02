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
 * Driver for "libxslt 1.1.23 - PHP"
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Libxslt1123phpProcessorDriver extends AProcessorDriver
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
				$available = FALSE;

				// php is needed
				exec('php -v 2> /dev/null | grep \'PHP\' | wc -l', $output);
				if ($output[0] != '0')
				{
					$available = TRUE;
				}

				if ($available)
				{
					// xsl extension is needed
					$output = NULL;
					exec('php --ri xsl | grep -P \'(libxslt Version => 1.1.23)|(XSL => enabled)\' | wc -l', $output);
					if ($output[0] == '2')
					{
						$available = TRUE;
					}
					else
					{
						$available = FALSE;
					}
				}

				return $available;

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
				$prefix = '"[LIBS]\Php\5.3.6\php.exe" -d extension="[PROCESSORS]\libxslt\1.1.23\php_xsl.dll"';
				break;

			case self::OS_LINUX:
				$prefix = 'php';
				break;

		}

		$phpScript = 'try {' .
			'	libxml_use_internal_errors(TRUE);' .
			'	$processor = new \XSLTProcessor();' .
			'	$processor->importStylesheet(new \SimpleXMLElement(\'[XSLT]\', 0, TRUE));' .
			'	$outputXml = $processor->transformToXml(new \SimpleXMLElement(\'[INPUT]\', 0, TRUE));' .
			'	file_put_contents(\'[OUTPUT]\', $outputXml);' .
			'}' .
			'catch (\Exception $e)' .
			'{' .
			'	$error = libxml_get_last_error();' .
			'	$errorMessage = $error->message . \': line \' . $error->line . \', column \' . $error->column . \', file \' . $error->file;' .
			'	file_put_contents(\'[ERROR]\', $errorMessage);' .
			'}';

		if (PHP_OS == self::OS_LINUX)
		{
			$phpScript = str_replace('\\', '\\\\', $phpScript);
			$phpScript = str_replace('$', '\\$', $phpScript);
		}

		$commandTemplate = $prefix . ' -r "' . $phpScript . '"';

		return $commandTemplate;
	}


	/**
	 * Full name of processor (with version)
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return 'libxslt 1.1.23 - PHP';
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
