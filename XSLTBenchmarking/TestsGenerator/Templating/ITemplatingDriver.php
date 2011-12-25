<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

/**
 * Interface for object for generating XSLT file from template.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface ITemplatingDriver
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($tmpDirectory);


	/**
	 * Generate the template with specifis variable and save the content into the file
	 *
	 * @param string $templatePath Path of template for generating
	 * @param string $outputPath Path output file
	 * @param array $settings Array of settings specific for driver
	 *
	 * @throws \XSLTBenchmarking\GenerateTemplateException Problem with generating
	 * @return void
	 */
	public function generate($templatePath, $outputPath, array $settings);


}
