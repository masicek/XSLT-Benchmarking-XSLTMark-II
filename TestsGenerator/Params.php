<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once __DIR__ . '/ParamsDrivers/XmlParamsDriver.php';

/**
 * Object for work with params of xslt template
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Params
{

	/**
	 * Driver for work witch params file.
	 *
	 * @var IParamsDriver
	 */
	private $driver;


	/**
	 * Choose the params driver by extension
	 *
	 * @param string $rootDirectoryPath The root directory of the tests collection
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 */
	public function __construct($rootDirectoryPath, $paramsFilePath, $tmpDirectoryPath)
	{
		$extension = pathinfo($paramsFilePath, PATHINFO_EXTENSION);
		switch ($extension)
		{
			case 'xml':
				$this->driver = new XmlParamsDriver($rootDirectoryPath, $paramsFilePath, $tmpDirectoryPath);
				break;

			default:
				throw new Exception('Not supported parameters type file.');
				break;
		}
	}


	/**
	 * Return the name of tests collection
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->driver->getTemplateName();
	}


	/**
	 * Return the path to the template file
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return $this->driver->getTemplatePath();
	}


	/**
	 * Return the type of templating
	 *
	 * @return string
	 */
	public function getTemplatingType()
	{
		return $this->driver->getTemplatingType();
	}


	/**
	 * Return the list of tests names
	 *
	 * @return array
	 */
	public function getTestsNames()
	{
		return $this->driver->getTestsNames();
	}


	/**
	 * Return the list of input files paths
	 * and paths of their expected output files for selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestFilesPaths($testName)
	{
		return $this->driver->getTestFilesPaths($testName);
	}


	/**
	 * Return the list of settings for the selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestSettings($testName)
	{
		return $this->driver->getTestSettings($testName);
	}


}
