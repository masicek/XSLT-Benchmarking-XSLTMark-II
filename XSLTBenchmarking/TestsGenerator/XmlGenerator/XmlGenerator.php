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

use PhpPath\P;

/**
 * Object for generating xml files by different xml generator.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class XmlGenerator extends \XSLTBenchmarking\DriversContainer
{


	/**
	 * Generate xml file
	 *
	 * @param string $outputPath The path of the output xml file
	 * @param array $settings The list of settings specific by selected xml generator
	 *
	 * @return void
	 */
	public function generate($outputPath, $templateDir, array $settings)
	{
		$omitXmlDeclaration = FALSE;
		if (isset($settings['omitXmlDeclaration']))
		{
			if ($settings['omitXmlDeclaration'] == 1)
			{
				$omitXmlDeclaration = TRUE;
			}
			unset($settings['omitXmlDeclaration']);
		}

		$this->driver->generate($outputPath, $templateDir, $settings);

		if ($omitXmlDeclaration)
		{
			$output = file_get_contents($outputPath);
			$output = preg_replace('/<\?xml[^?]+\?>/', '', $output);
			file_put_contents($outputPath, $output);
		}
	}


}
