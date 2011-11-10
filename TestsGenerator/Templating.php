<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/TemplatingDrivers/SimpleTemplatingDriver.php';
require_once __DIR__ . '/TemplatingDrivers/SmartyTemplatingDriver.php';


/**
 * Class for generating XSLT file from template.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Templating
{

	/**
	 * Driver for generating XSLT file.
	 *
	 * @var ITemplatingDriver
	 */
	private $driver;


	/**
	 * Choose the templating driver
	 *
	 * @param string $type Select templating driver
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($type, $tmpDirectory)
	{
		switch ($type)
		{
			case 'simple':
				$this->driver = new SimpleTemplatingDriver();
				break;

			case 'smarty':
				$this->driver = new SmartyTemplatingDriver($tmpDirectory);
				break;

			default:
				throw new Exception('Not supported templating type.');
				break;
		}
	}


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
		$this->driver->generate($templatePath, $outputPath, $settings);
	}


}
