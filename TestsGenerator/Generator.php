<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/Templating.php';
require_once __DIR__ . '/Test.php';
require_once __DIR__ . '/Params.php';
require_once __DIR__ . '/Directory.php';


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
		$callerDirectoryPath = dirname($_SERVER['SCRIPT_FILENAME']);

		$templatesDirectory = Directory::make($callerDirectoryPath, $templatesDirectory . '/');
		$testsDirectory = Directory::make($callerDirectoryPath, $testsDirectory . '/');
		$tmpDirectory = Directory::make($callerDirectoryPath, $tmpDirectory . '/');

		Directory::check($templatesDirectory);
		Directory::check($testsDirectory);
		Directory::check($tmpDirectory);

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
	public function addTests($templateDirectory, $testParamsFile = 'params.xml')
	{
		$rootDirectory = Directory::make($this->templatesDirectory, $templateDirectory);
		$params = new Params($rootDirectory, $testParamsFile, $this->tmpDirectory);
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
			$destinationInputPath = Directory::make($destinationDirectory, basename($inputPath));
			$destinationOutputPath = Directory::make($destinationDirectory, basename($outputPath));
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
		$couplesOutput = new \DOMDocument();
		$couplesOutput->formatOutput = true;
		$couplesElement = $couplesOutput->createElement('couples');
		foreach ($couples as $input => $output)
		{
			$coupleElement = $couplesOutput->createElement('couple');

			$inputAttribute = $couplesOutput->createAttribute('input');
			$inputAttribute->value = $input;

			$outputAttribute = $couplesOutput->createAttribute('output');
			$outputAttribute->value = $output;

			$coupleElement->appendChild($inputAttribute);
			$coupleElement->appendChild($outputAttribute);
			$couplesElement->appendChild($coupleElement);
		}

		$couplesOutput->appendChild($couplesElement);

		$couplesOutput->save(Directory::make($test->getPath(), '/couples.xml'));
	}


}
