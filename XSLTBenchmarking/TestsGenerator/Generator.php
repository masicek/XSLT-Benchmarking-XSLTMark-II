<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once __DIR__ . '/../Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Tests generator for XSTL Benchamrking
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Generator
{

	/**
	 * Factory class for making new objects
	 *
	 * @var \XSLTBenchmarking\Factory
	 */
	private $factory;

	/**
	 * Object for reading params of tests templates
	 *
	 * @var \XSLTBenchmarking\TestsGenerator\Params
	 */
	private $params;

	/**
	 * Object for generating XSLT from template of XSLT
	 *
	 * @var \XSLTBenchmarking\TestsGenerator\Templating
	 */
	private $templating;

	/**
	 * Object for generating params of test
	 *
	 * @var \XSLTBenchmarking\TestsRunner\Params
	 */
	private $paramsTest;

	/**
	 * Root directory of templates for all tests
	 *
	 * @var string
	 */
	private $templatesDirectory;

	/**
	 * Root directory of generated tests
	 *
	 * @var string
	 */
	private $testsDirectory;

	/**
	 * List of test templates for generating tests
	 *
	 * @var array of Test
	 */
	private $templates = array();


	/**
	 * Object configuration
	 *
	 * @param \XSLTBenchmarking\Factory $factory Factory class for making new objects
	 * @param \XSLTBenchmarking\TestsGenerator\Params $params Object for reading params of tests templates
	 * @param \XSLTBenchmarking\TestsGenerator\Templating $templating Object for generating XSLT from template of XSLT
	 * @param \XSLTBenchmarking\TestsRunner\Params $paramsTest Object for generating params of test
	 * @param string $templatesDirectory The root directory of all tests templates
	 * @param string $testsDirectory The root directory of all generated tests
	 */
	public function __construct(
		\XSLTBenchmarking\Factory $factory,
		\XSLTBenchmarking\TestsGenerator\Params $params,
		\XSLTBenchmarking\TestsGenerator\Templating $templating,
		\XSLTBenchmarking\TestsRunner\Params $paramsTest,
		$templatesDirectory,
		$testsDirectory)
	{
		P::cd($templatesDirectory);
		P::cd($testsDirectory);

		$this->factory = $factory;
		$this->params = $params;
		$this->templating = $templating;
		$this->paramsTest = $paramsTest;
		$this->templatesDirectory = $templatesDirectory;
		$this->testsDirectory = $testsDirectory;
	}


	/**
	 * Return registered test templates.
	 *
	 * @return array
	 */
	public function getTests()
	{
		return $this->templates;
	}


	/**
	 * Register defined tests for generating
	 *
	 * @param string $templateDirectory The subdirectory of the root directory
	 * of templates containing template of generated tests
	 * @param string $paramsFiles File defined tests
	 *
	 * @throws \XSLTBenchmarking\CollisionException Duplicate name of test
	 * @return void
	 */
	public function addTests($templateDirectory, $testParamsFile = '__params.xml')
	{
		$testParamsPath = P::mcf($this->templatesDirectory, $templateDirectory, $testParamsFile);
		$this->params->setFile($testParamsPath);
		$templateName = $this->params->getTemplateName();
		$templatePath = $this->params->getTemplatePath();
		$templatingType = $this->params->getTemplatingType();
		foreach ($this->params->getTestsNames() as $testName)
		{
			$fullName = $templateName . ' - ' . $testName;
			if (isset($this->templates[$fullName]))
			{
				throw new \XSLTBenchmarking\CollisionException('Duplicate name of test "' . $fullName . '"');
			}

			$test = $this->factory->getTestsGeneratorTest($fullName);
			$test->setTemplatePath($templatePath);
			$test->setTemplatingType($templatingType);
			$test->setPath($this->testsDirectory);
			$test->addFilesPaths($this->params->getTestFilesPaths($testName));
			$test->addSettings($this->params->getTestSettings($testName));
			$test->setParamsFilePath($this->params->getTestParamsFileName($testName));
			$this->templates[$fullName] = $test;
		}
	}


	/**
	 * Generate all registered tests
	 *
	 * @return int Number of generated tests
	 */
	public function generateAll()
	{
		$tests = $this->getTests();
		foreach ($tests as $test)
		{
			$this->generateTest($test);
		}

		return count($tests);
	}


	/**
	 * Generate input test
	 *
	 * @param Test $test Generated test
	 *
	 * @return void
	 */
	private function generateTest(Test $test)
	{
		$destinationDirectory = $test->getPath();
		if (!is_dir($destinationDirectory))
		{
			mkdir($destinationDirectory);
		}

		// copy files to tests directory
		foreach ($test->getFilesPaths() as $inputPath => $outputPath)
		{
			$destinationInputPath = P::m($destinationDirectory, basename($inputPath));
			$destinationOutputPath = P::m($destinationDirectory, basename($outputPath));
			copy($inputPath, $destinationInputPath);
			copy($outputPath, $destinationOutputPath);
		}

		// generate template
		$this->templating->setDriver($test->getTemplatingType());
		$this->templating->generate($test->getTemplatePath(), $test->getXsltPath(), $test->getSettings());

		// generate file with params of generated test
		$this->paramsTest->setFile($test->getParamsFilePath());
		$this->paramsTest->generate(
			$test->getName(),
			$test->getXsltName(),
			$test->getFilesPaths()
		);
	}


}
