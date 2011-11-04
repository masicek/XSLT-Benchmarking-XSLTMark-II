<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/IParamsDriver.php';
require_once __DIR__ . '/../Directory.php';
require_once __DIR__ . '/XmlGenerator.php';


/**
 * Xml prarams for
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class XmlParamsDriver implements IParamsDriver
{

	/**
	 * Params lodaded from xml file
	 *
	 * @var \DOMDocument
	 */
	private $params;

	/**
	 * Root directory of tests collection
	 *
	 * @var string
	 */
	private $rootDirectory;


	/**
	 * Object configuration
	 */
	public function __construct($rootDirectory, $paramsFile)
	{
		$this->rootDirectory = $rootDirectory;
		$this->params = new \DOMDocument();
		$this->params->load(Directory::make($this->rootDirectory, $paramsFile));
	}


	/**
	 * Return the name of tests collection
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->params->getElementsByTagName('tests')->item(0)->getAttribute('name');
	}


	/**
	 * Return the path to the template file
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		$templateFile = $this->params->getElementsByTagName('tests')->item(0)->getAttribute('template');
		return Directory::make($this->rootDirectory, $templateFile);
	}


	/**
	 * Return the type of templating
	 *
	 * @return string
	 */
	public function getTemplatingType()
	{
		return $this->params->getElementsByTagName('tests')->item(0)->getAttribute('templatingType');
	}


	/**
	 * Return the list of paths to xml files for testing
	 *
	 * @param string $tmpDir Temporary directory for generating xml files
	 *
	 * @return array
	 */
	public function getXmlFilesPaths($tmpDir)
	{
		$files = array();
		$filesDefinitions = $this->params->getElementsByTagName('xml')->item(0);

		// existed xml files
		$fileIdx = 0;
		while ($fileDefinition = $filesDefinitions->getElementsByTagName('file')->item($fileIdx))
		{
			$files[] = Directory::make($this->rootDirectory, $fileDefinition->nodeValue);
			$fileIdx++;
		}

		// generated xml files
		$xmlGenerator = new XmlGenerator();
		$fileIdx = 0;
		while ($generatorDefinition = $filesDefinitions->getElementsByTagName('generator')->item($fileIdx))
		{
			$type = $generatorDefinition->getAttribute('name');
			$outputPath = Directory::make($tmpDir, $generatorDefinition->getAttribute('output'));

			// read settings
			$settings = array();
			$settingIdx = 0;
			while ($settingDefinition = $generatorDefinition->getElementsByTagName('setting')->item($settingIdx))
			{
				$name = $settingDefinition->getAttribute('name');
				$value = $settingDefinition->nodeValue;
				$settings[$name] = $value;
				$settingIdx++;
			}

			$xmlGenerator->generate($type, $outputPath, $settings);
			$files[] = $outputPath;
			$fileIdx++;
		}

		return $files;
	}


	/**
	 * Return the list of tests with their settings
	 *
	 * @return array
	 */
	public function getTests()
	{
		$tests = array();

		$testsDefinitions = $this->params->getElementsByTagName('test');
		$testIdx = 0;
		while ($testDefinition = $testsDefinitions->item($testIdx))
		{
			$testName = $testDefinition->getAttribute('name');
			$tests[$testName] = array();

			// all settings in test
			$settingsDefinitions = $testDefinition->getElementsByTagName('setting');
			$settingIdx = 0;
			while ($settingDefinition = $settingsDefinitions->item($settingIdx))
			{
				$name = $settingDefinition->getAttribute('name');
				$value = $settingDefinition->nodeValue;
				$tests[$testName][$name] = $value;
				$settingIdx++;
			}
			$testIdx++;
		}

		return $tests;
	}


}
