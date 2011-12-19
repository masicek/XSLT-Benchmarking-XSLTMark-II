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
	 */
	public function __construct($paramsFilePath, $tmpDirectoryPath)
	{
		P::cf($paramsFilePath);
		P::cd($tmpDirectoryPath);
		$extension = pathinfo($paramsFilePath, PATHINFO_EXTENSION);
		parent::__construct($extension, $paramsFilePath, $tmpDirectoryPath);
	}


}
