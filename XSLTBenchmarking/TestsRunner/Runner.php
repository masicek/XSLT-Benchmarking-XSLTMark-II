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
	 * Factory class for making new objects
	 *
	 * @var \XSLTBenchmarking\Factory
	 */
	private $factory;

	/**
	 * Object for reading params of tests
	 *
	 * @var \XSLTBenchmarking\TestsRunner\Params
	 */
	private $params;

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
	 * @param \XSLTBenchmarking\Factory $factory Factory class for making new objects
	 * @param \XSLTBenchmarking\TestsRunner\Params $params Object for reading params of tests
	 * @param type $testsDirectory The root directory of all generated tests
	 */
	public function __construct(
		\XSLTBenchmarking\Factory $factory,
		\XSLTBenchmarking\TestsRunner\Params $params,
		$testsDirectory
	)
	{
		$testsDirectory = P::mcd($testsDirectory);

		$this->factory = $factory;
		$this->params = $params;
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

		$this->params->setFile($testParamsPath);
		$name = $this->params->getName();

		if (isset($this->tests[$name]))
		{
			throw new \XSLTBenchmarking\CollisionException('Duplicate name of test "' . $name . '"');
		}

		$test = $this->factory->getTestsRunnerTest($name);
		$test->setTemplatePath($this->params->getTemplatePath());
		$test->addCouplesPaths($this->params->getCouplesPaths());

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
