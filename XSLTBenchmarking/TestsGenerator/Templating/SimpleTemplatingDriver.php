<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once __DIR__ . '/ITemplatingDriver.php';
require_once ROOT . '/Exceptions.php';


use PhpPath\P;

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
	 * @throws \XSLTBenchmarking\GenerateTemplateException Problem with generating
	 * @return void
	 */
	public function generate($templatePath, $outputPath, array $settings = array())
	{
		P::cf($templatePath);

		if (!copy($templatePath, $outputPath))
		{// @codeCoverageIgnoreStart
			throw new \XSLTBenchmarking\GenerateTemplateException('Cannot create file "' . $outputFile . '".');
		}// @codeCoverageIgnoreEnd
	}


}
