<?php

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
	 */
	public function __construct($rootDirectory, $paramsFile)
	{
		$extension = pathinfo($paramsFile, PATHINFO_EXTENSION);
		switch ($extension)
		{
			case 'xml':
				$this->driver = new XmlParamsDriver($rootDirectory, $paramsFile);
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
	 * Return the list of paths to files for testing
	 *
	 * @param string $tmpDir Temporary directory for generating xml files
	 *
	 * @return array
	 */
	public function getXmlFilesPaths($tmpDir)
	{
		return $this->driver->getXmlFilesPaths($tmpDir);
	}


	/**
	 * Return the list of tests with their variables
	 *
	 * @return array
	 */
	public function getTests()
	{
		return $this->driver->getTests();
	}


}
