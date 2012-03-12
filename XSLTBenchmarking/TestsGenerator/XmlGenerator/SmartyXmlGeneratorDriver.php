<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once __DIR__ . '/IXmlGeneratorDriver.php';
require_once ROOT . '/TestsGenerator/Templating/SmartyTemplatingDriver.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Exceptions.php';

use PhpPath\P;

/**
 * Driver for generating XML files by Smarty.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SmartyXmlGeneratorDriver extends SmartyTemplatingDriver implements IXmlGeneratorDriver
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($tmpDirectory)
	{
		parent::__construct($tmpDirectory);
	}


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
		try
		{
			$templatePath = P::m($templateDir, $settings['template']);
			parent::generate($templatePath, $outputPath, $settings);
		}
		catch (\XSLTBenchmarking\GenerateTemplateException $e)
		{
			throw new \XSLTBenchmarking\GenerateXmlException($e->getMessage());
		}
	}


}
