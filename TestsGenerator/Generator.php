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
class Generator extends Templating
{

	/**
	 * Root directory of templates for all tests
	 *
	 * @var string
	 */
	private $templatesDir;

	/**
	 * Root directory of generated tests
	 *
	 * @var string
	 */
	private $testsDir;

	/**
	 * Directory temporary files
	 *
	 * @var string
	 */
	private $tmpDir;

	/**
	 * List of test templates for generating tests
	 *
	 * @var array of Test
	 */
	private $templates = array();


	/**
	 * Object configuration
	 */
	public function __construct($templatesDir, $testsDir, $tmpDir)
	{
		$dir = __DIR__ . '/../';
		$templatesDir = Directory::make($dir, $templatesDir . '/');
		$testsDir = Directory::make($dir, $testsDir . '/');
		$tmpDir = Directory::make($dir, $tmpDir . '/');

		Directory::check($templatesDir);
		Directory::check($testsDir);
		Directory::check($tmpDir);

		parent::__construct($tmpDir);

		$this->templatesDir = $templatesDir;
		$this->testsDir = $testsDir;
		$this->tmpDir = $tmpDir;
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
	 * @param string $paramsFiles File defined tests
	 */
	public function addTests($templateDir, $testParamsFile = 'params.xml')
	{
		$params = new Params(Directory::make($this->templatesDir, $templateDir), $testParamsFile);
		$templateName = $params->getTemplateName();
		$templatePath = $params->getTemplatePath();
		$xmlFilesPaths = $params->getXmlFilesPaths($this->tmpDir);
		foreach ($params->getTests() as $testName => $variables)
		{
			$test = new Test($templateName . ' - ' . $testName);
			$test->setTemplatePath($templatePath);
			$test->setPath($this->testsDir);
			$test->addXmlFilesPaths($xmlFilesPaths);
			$test->addVariables($variables);
			$this->templates[] = $test;
		}
	}


	/**
	 * Generate all registered tests
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
	 */
	private function generateTest(Test $test)
	{
		$testDir = $test->getPath();
		if (!is_dir($testDir))
		{
			mkdir($testDir);
		}

		// copy xml files to tests directory
		$destinationDirectory = $test->getPath();
		foreach ($test->getXmlFilesPaths() as $source)
		{
			$destination = Directory::make($destinationDirectory, basename($source));
			copy($source, $destination);
		}

		$this->generate($test->getTemplatePath(), $test->getVariables(), $test->getXsltPath());
	}


}
