<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/DriversContainer.php';

use PhpPath\P;

/**
 * Object for work with params of test
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Params extends \XSLTBenchmarking\DriversContainer
{


	/**
	 * Choose the params driver by extension
	 *
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct($paramsFilePath)
	{
		P::cf($paramsFilePath);
		$extension = pathinfo($paramsFilePath, PATHINFO_EXTENSION);
		parent::__construct($extension, $paramsFilePath);
	}


}
