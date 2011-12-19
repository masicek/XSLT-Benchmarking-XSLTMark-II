<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once __DIR__ . '/ITemplatingDriver.php';
require_once ROOT . '/Exceptions.php';

/**
 * Simple templating - only copy input file into output file.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SimpleTemplatingDriver implements ITemplatingDriver
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($tmpDirectory)
	{
	}


	/**
	 * Copy input file into output file
	 *
	 * @param string $templatePath Path of file to copy
	 * @param string $outputPath Path output file
	 * @param array $settings Settings are not use in this driver
	 *
	 * @return void
	 */
	public function generate($templatePath, $outputPath, array $settings = array())
	{
		if (!copy($templatePath, $outputPath))
		{// @codeCoverageIgnoreStart
			throw new \XSLTBenchmark\CopyFileException('Cannot create file "' . $outputFile . '".');
		}// @codeCoverageIgnoreEnd
	}


}
