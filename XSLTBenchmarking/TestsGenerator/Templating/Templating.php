<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/DriversContainer.php';

use \PhpPath\P;

/**
 * Class for generating XSLT file from template.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Templating extends \XSLTBenchmarking\DriversContainer
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
	public function generate($templatePath, $outputPath, array $settings = array())
	{
		P::cf($templatePath);
		$this->driver->generate($templatePath, $outputPath, $settings);
	}


}
