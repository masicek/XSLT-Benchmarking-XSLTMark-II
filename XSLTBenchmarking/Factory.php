<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking;

require_once __DIR__ . '/TestsGenerator/Test.php';
require_once __DIR__ . '/TestsRunner/Test.php';
require_once __DIR__ . '/Reports/Report.php';

/**
 * Factory class for making new objects.
 * It was created for better testing.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Factory
{


	/**
	 * Make new Test class and return it
	 *
	 * @param string $name The human-redable name of the test
	 *
	 * @return \XSLTBenchmarking\TestsGenerator\Test
	 */
	public function getTestsGeneratorTest($name)
	{
		return new TestsGenerator\Test($name);
	}


	/**
	 * Make new Test class and return it
	 *
	 * @param string $name The human-redable name of the test
	 *
	 * @return \XSLTBenchmarking\TestsRunner\Test
	 */
	public function getTestsRunnerTest($name)
	{
		return new TestsRunner\Test($name);
	}


	/**
	 * Make new Report class and return it
	 *
	 * @param string $testName Name of the reported test
	 * @param string $templatePath Path of the tests XSLT template
	 *
	 * @return \XSLTBenchmarking\Reports\Report
	 */
	public function getReport($testName, $templatePath)
	{
		return new Reports\Report($testName, $templatePath);
	}


}
