<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

/**
 * Abstract parent for object for collect params about test.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IParamsDriver
{


	/**
	 * Set the params file.
	 *
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct($paramsFilePath);


	/**
	 * Return the name of test
	 *
	 * @return string
	 */
	public function getName();


	/**
	 * Return the path to the XSLT template
	 *
	 * @return string
	 */
	public function getTemplatePath();


	/**
	 * Return the path to the XML files for testing
	 * - input
	 * - expected output
	 *
	 * @return array ([input] => [expected output])
	 */
	public function getCouplesPaths();


}
