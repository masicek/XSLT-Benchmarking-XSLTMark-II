<?php

/**
 * XSLT Benchmarking
 * @link git@github.com:masicek/XSLT-Benchmarking.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

/**
 * Interface for object for generating XSLT file from template.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface ITemplatingDriver
{


	/**
	 * Generate the template with specifis variable and save the content into the file
	 *
	 * @param string $templatePath Path of template for generating
	 * @param string $outputPath Path output file
	 * @param array $settings Array of settings specific for driver
	 *
	 * @return void
	 */
	public function generate($templatePath, $outputPath, array $settings);


}
