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
			// TODO in future based on driver
			$name = '__params.xml';
		}
		return $name;
	}


	/**
	 * Choose the params driver by extension of params file
	 *
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function setFile($paramsFilePath)
	{
		P::cf($paramsFilePath);
		$extension = pathinfo(P::m($paramsFilePath), PATHINFO_EXTENSION);
		return $this->setDriver($extension, $paramsFilePath);
	}


}
