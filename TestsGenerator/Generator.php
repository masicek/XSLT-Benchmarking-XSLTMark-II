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
	 * @param string $templateDir The subdirectory of the root directory of templates
	 * containing template of generated tests
	 * @param string $paramsFiles File defined tests
	 *
	 * @return void
	 */
	public function addTests($templateDir, $testParamsFile = 'params.xml')
	{
		$params = new Params(Directory::make($this->templatesDir, $templateDir), $testParamsFile);
		$templateName = $params->getTemplateName();
		$templatePath = $params->getTemplatePath();
		$templatingType = $params->getTemplatingType();
		$xmlFilesPaths = $params->getXmlFilesPaths($this->tmpDir);
		foreach ($params->getTests() as $testName => $settings)
		{
			$test = new Test($templateName . ' - ' . $testName);
			$test->setTemplatePath($templatePath);
			$test->setTemplatingType($templatingType);
			$test->setPath($this->testsDir);
			$test->addXmlFilesPaths($xmlFilesPaths);
			$test->addSettings($settings);
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

		// generate template
		$templating = new Templating($test->getTemplatingType(), $this->tmpDir);
		$templating->generate($test->getTemplatePath(), $test->getXsltPath(), $test->getSettings());
	}


}
