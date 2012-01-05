<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

/**
 * Abstract parent for object for collect params about templates.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IParamsDriver
{


	/**
	 * Choose the params driver by extension
	 *
	 * @param \XSLTBenchmarking\TestsGenerator\XmlGenerator $xmlGenerator Object for generating XML files
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct(
		\XSLTBenchmarking\TestsGenerator\XmlGenerator $xmlGenerator,
		$tmpDirectoryPath,
		$paramsFilePath
	);


	/**
	 * Return the name of tests collection
	 *
	 * @return string
	 */
	public function getTemplateName();


	/**
	 * Return the path to the template file
	 *
	 * @return string
	 */
	public function getTemplatePath();


	/**
	 * Return the type of templating
	 *
	 * @return string
	 */
	public function getTemplatingType();


	/**
	 * Return the list of tests names
	 *
	 * @return array
	 */
	public function getTestsNames();


	/**
	 * Return the list of input files paths
	 * and paths of their expected output files for selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestFilesPaths($testName);


	/**
	 * Return the list of settings for the selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestSettings($testName);


	/**
	 * Return the name of file with params of the test.
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return string|NULL
	 */
	public function getTestParamsFileName($testName);


}
