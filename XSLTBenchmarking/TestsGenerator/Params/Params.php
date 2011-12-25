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
 * Object for work with params of xslt template
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Params extends \XSLTBenchmarking\DriversContainer
{


	/**
	 * Choose the params driver by extension
	 *
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct($paramsFilePath, $tmpDirectoryPath)
	{
		P::cf($paramsFilePath);
		P::cd($tmpDirectoryPath);
		$extension = pathinfo($paramsFilePath, PATHINFO_EXTENSION);
		parent::__construct($extension, $paramsFilePath, $tmpDirectoryPath);
	}


	/**
	 * Return the name of file with params of the test
	 * Default value is '__params.xml'.
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return string
	 */
	public function getTestParamsFileName($testName)
	{
		$name = $this->driver->getTestParamsFileName($testName);
		if (!$name)
		{
			$name = '__params.xml';
		}
		return $name;
	}


}
