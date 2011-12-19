<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once __DIR__ . '/Templating/Templating.php';
require_once __DIR__ . '/Test.php';
require_once __DIR__ . '/Params/Params.php';
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
	 * Directory temporary files
	 *
	 * @var string
	 */
	private $tmpDirectory;

	/**
	 * List of test templates for generating tests
	 *
	 * @var array of Test
	 */
	private $templates = array();


	/**
	 * Object configuration
	 *
	 * @param type $templatesDirectory The root directory of all tests templates
	 * @param type $testsDirectory The root directory of all generated tests
	 * @param type $tmpDirectory The temporary directory
	 */
	public function __construct($templatesDirectory, $testsDirectory, $tmpDirectory)
	{
		P::cd($templatesDirectory);
		P::cd($testsDirectory);
		P::cd($tmpDirectory);

		$this->templatesDirectory = $templatesDirectory;
		$this->testsDirectory = $testsDirectory;
		$this->tmpDirectory = $tmpDirectory;
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
	 * @return void
	 */
	public function addTests($templateDirectory, $testParamsFile = '__params.xml')
	{
		$testParamsPath = P::mcf($this->templatesDirectory, $templateDirectory, $testParamsFile);
		$params = new Params($testParamsPath, $this->tmpDirectory);
		$templateName = $params->getTemplateName();
		$templatePath = $params->getTemplatePath();
		$templatingType = $params->getTemplatingType();
		foreach ($params->getTestsNames() as $testName)
		{
			$test = new Test($templateName . ' - ' . $testName);
			$test->setTemplatePath($templatePath);
			$test->setTemplatingType($templatingType);
			$test->setPath($this->testsDirectory);
			$test->addFilesPaths($params->getTestFilesPaths($testName));
			$test->addSettings($params->getTestSettings($testName));
			$this->templates[] = $test;
		}
	}


	/**
	 * Generate all registered tests
	 *
	 * @return void
	 */
	public function generateAll()
	{
		foreach ($this->getTests() as $test)
		{
			$this->generateTest($test);
		}
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
		$testDirectory = $test->getPath();
		if (!is_dir($testDirectory))
		{
			mkdir($testDirectory);
		}

		// copy files to tests directory
		$destinationDirectory = $test->getPath();
		foreach ($test->getFilesPaths() as $inputPath => $outputPath)
		{
			$destinationInputPath = P::m($destinationDirectory, basename($inputPath));
			$destinationOutputPath = P::m($destinationDirectory, basename($outputPath));
			copy($inputPath, $destinationInputPath);
			copy($outputPath, $destinationOutputPath);
		}

		// generate file with couple of 'input' and 'expected output' files
		$this->generateInputOutputCouples($test);

		// generate template
		$templating = new Templating($test->getTemplatingType(), $this->tmpDirectory);
		$templating->generate($test->getTemplatePath(), $test->getXsltPath(), $test->getSettings());
	}


	/**
	 * Create XML file containing couples of input end expected output files for testing
	 *
	 * @param Test $test Test for which XML file will be created
	 *
	 * @return void
	 */
	private function generateInputOutputCouples(Test $test)
	{
		// get base name of couples
		$couplesPaths = $test->getFilesPaths();
		$couplesKeys = array_map('basename', array_keys($couplesPaths));
		$couplesValues = array_map('basename', $couplesPaths);
		$couples = array_combine($couplesKeys, $couplesValues);

		// make xml file
		$testDef = new \DOMDocument();
		$testDef->formatOutput = true;
		$testElement = $testDef->createElement('test');

		// name of template for testing
		$templateAttribute = $testDef->createAttribute('template');
		$templateAttribute->value = $test->getXsltName();
		$testElement->appendChild($templateAttribute);

		foreach ($couples as $input => $output)
		{
			$coupleElement = $testDef->createElement('couple');

			$inputAttribute = $testDef->createAttribute('input');
			$inputAttribute->value = $input;

			$outputAttribute = $testDef->createAttribute('output');
			$outputAttribute->value = $output;

			$coupleElement->appendChild($inputAttribute);
			$coupleElement->appendChild($outputAttribute);
			$testElement->appendChild($coupleElement);
		}

		$testDef->appendChild($testElement);

		$testDef->save(P::m($test->getPath(), '__params.xml'));
	}


}
