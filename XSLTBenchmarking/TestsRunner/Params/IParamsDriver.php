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
	 * Function for generating new paramas file
	 *
	 * @param string $name Name of the test
	 * @param string $templatePath Path of tested XSLT template
	 * @param array $couplesPaths ([input] => [output], ...)
	 *
	 * @return void
	 */
	public function generate($name, $templatePath, array $couplesPaths);


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
