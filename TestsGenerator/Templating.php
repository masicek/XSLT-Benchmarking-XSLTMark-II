<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

define ('DRIVERS_TEMPLATING', __DIR__ . '/TemplatingDrivers');

require_once DRIVERS_TEMPLATING . '/SimpleTemplatingDriver.php';
require_once DRIVERS_TEMPLATING . '/SmartyTemplatingDriver.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Exceptions.php';

use \PhpPath\P;

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
		P::cd($tmpDirectory);

		switch ($type)
		{
			case 'simple':
				$this->driver = new SimpleTemplatingDriver();
				break;

			case 'smarty':
				$this->driver = new SmartyTemplatingDriver($tmpDirectory);
				break;

			default:
				throw new \XSLTBenchmark\InvalidArgumentException('Not supported templating type.');
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
		P::cf($templatePath);
		$this->driver->generate($templatePath, $outputPath, $settings);
	}


}
