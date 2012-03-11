<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

/**
 * Inerface for drivers for generating XML files.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IXmlGeneratorDriver
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($tmpDirectory);


	/**
	 * Generate xml file
	 *
	 * @param string $outputPath The path of the output xml file
	 * @param array $settings The list of settings specific by selected xml generator
	 *
	 * @return void
	 */
	public function generate($outputPath, $templateDir, array $settings);


}
