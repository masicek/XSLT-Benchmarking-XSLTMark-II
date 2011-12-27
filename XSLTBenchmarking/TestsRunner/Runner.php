<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Runner
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Runner
{


	/**
	 * Root directory of generated tests
	 *
	 * @var string
	 */
	private $testsDirectory;


	/**
	 * Tests for generating
	 *
	 * @var array of Test
	 */
	private $tests = array();


	/**
	 * Object configuration
	 *
	 * @param type $testsDirectory The root directory of all generated tests
	 */
	public function __construct($testsDirectory)
	{
		$testsDirectory = P::mcd($testsDirectory);

		$this->testsDirectory = $testsDirectory;
	}


	/**
	 * Register defined tests for runnig
	 *
	 * @param string $testDirectory The subdirectory of the root directory
	 * of tests containing test for running
	 * @param string $paramsFiles File defined test
	 *
	 * @return void
	 */
	public function addTest($testDirectory, $testParamsFile = '__params.xml')
	{
		$testParamsPath = P::mcf($this->testsDirectory, $testDirectory, $testParamsFile);

		$params = new Params($testParamsPath);
		$name = $params->getName();

		if (isset($this->tests[$name]))
		{
			throw new \XSLTBenchmarking\CollisionException('Duplicate name of test "' . $name . '"');
		}

		$test = new Test($name);
		$test->setTemplatePath($params->getTemplatePath());
		$test->addCouplesPaths($params->getCouplesPaths());

		$this->tests[$name] = $test;
	}


	/**
	 * Return defined tests for running
	 *
	 * @return array
	 */
	public function getTests()
	{
		return $this->tests;
	}


	/**
	 * Run all added tests
	 */
	public function runAll()
	{
		// TODO
	}


}
