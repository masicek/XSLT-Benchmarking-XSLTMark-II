<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once __DIR__ . '/IParamsDriver.php';
require_once __DIR__ . '/XmlGenerator.php';
require_once __DIR__ . '/../../Libs/PhpDirectory/Directory.php';

use PhpDirectory\Directory;

/**
 * Xml prarams for the collection of tests
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class XmlParamsDriver implements IParamsDriver
{

	/**
	 * Params loaded from xml file
	 *
	 * @var \DOMDocument
	 */
	private $params;

	/**
	 * The root directory of the tests collection
	 *
	 * @var string
	 */
	private $rootDirectoryPath;

	/**
	 * The path of the temporary directory
	 *
	 * @var string
	 */
	private $tmpDirectoryPath;

	/**
	 * The cache of list all files path and their id defined in params file
	 *
	 * @var string
	 */
	private $allFilesPaths = NULL;


	/**
	 * Choose the params driver by extension
	 *
	 * @param string $rootDirectoryPath The root directory of the tests collection
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 */
	public function __construct($rootDirectoryPath, $paramsFilePath, $tmpDirectoryPath)
	{
		$this->rootDirectoryPath = $rootDirectoryPath;
		$this->tmpDirectoryPath = $tmpDirectoryPath;
		$this->params = new \DOMDocument();
		$this->params->load(Directory::make($this->rootDirectoryPath, $paramsFilePath));
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
		return Directory::make($this->rootDirectoryPath, $templateFile);
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
	 * Return the list of tests names
	 *
	 * @return array
	 */
	public function getTestsNames()
	{
		$names = array();

		$testsDefinitions = $this->params->getElementsByTagName('test');
		$testIdx = 0;
		while ($testDefinition = $testsDefinitions->item($testIdx))
		{
			$testName = $testDefinition->getAttribute('name');
			$names[] = $testName;
			$testIdx++;
		}

		return $names;
	}


	/**
	 * Return the list of input files paths
	 * and paths of their expected output files for selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestFilesPaths($testName)
	{
		$files = array();

		// TODO optimization - get selected test by xpath
		$testsDefinitions = $this->params->getElementsByTagName('test');
		$testIdx = 0;
		while ($testDefinition = $testsDefinitions->item($testIdx))
		{
			$testIdx++;

			$selectedTestName = $testDefinition->getAttribute('name');
			if ($selectedTestName != $testName)
			{
				continue;
			}

			// all data in test
			$allFiles = $this->getAllFilesPaths();
			$filesDefinitions = $testDefinition->getElementsByTagName('file');
			$fileIdx = 0;
			while ($fileDefinition = $filesDefinitions->item($fileIdx))
			{
				$inputId = $fileDefinition->getAttribute('input');
				$outputId = $fileDefinition->getAttribute('output');
				$input = $allFiles[$inputId];
				$output = $allFiles[$outputId];
				$files[$input] = $output;
				$fileIdx++;
			}

			break;
		}

		return $files;
	}


	/**
	 * Return the list of settings for the selected test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return array
	 */
	public function getTestSettings($testName)
	{
		$settings = array();

		// TODO optimization - get selected test by xpath
		$testsDefinitions = $this->params->getElementsByTagName('test');
		$testIdx = 0;
		while ($testDefinition = $testsDefinitions->item($testIdx))
		{
			$testIdx++;

			$selectedTestName = $testDefinition->getAttribute('name');
			if ($selectedTestName != $testName)
			{
				continue;
			}

			// all settings in test
			$settingsDefinitions = $testDefinition->getElementsByTagName('setting');
			$settingIdx = 0;
			while ($settingDefinition = $settingsDefinitions->item($settingIdx))
			{
				$name = $settingDefinition->getAttribute('name');
				$value = $settingDefinition->nodeValue;
				$settings[$name] = $value;
				$settingIdx++;
			}

			break;
		}

		return $settings;
	}


	/**
	 * Return list of possible paths of files for testing with their ids
	 *
	 * @return array ([id] => [path], ...)
	 */
	private function getAllFilesPaths()
	{
		if ($this->allFilesPaths)
		{
			return $this->allFilesPaths;
		}


		$this->allFilesPaths = $this->createAllFilesPaths();
		return $this->allFilesPaths;
	}


	/**
	 * Create list of possible paths of files for testing with their ids
	 *
	 * @return array ([id] => [path], ...)
	 */
	private function createAllFilesPaths()
	{
		$files = array();
		$filesDefinitions = $this->params->getElementsByTagName('files')->item(0);

		// existed xml files
		$fileIdx = 0;
		while ($fileDefinition = $filesDefinitions->getElementsByTagName('file')->item($fileIdx))
		{
			$id = $fileDefinition->getAttribute('id');
			$files[$id] = Directory::make($this->rootDirectoryPath, $fileDefinition->nodeValue);
			$fileIdx++;
		}

		// generated xml files
		$xmlGenerator = new XmlGenerator();
		$fileIdx = 0;
		while ($generatorDefinition = $filesDefinitions->getElementsByTagName('generated')->item($fileIdx))
		{
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

			$type = $generatorDefinition->getAttribute('generator');
			$outputPath = Directory::make($this->tmpDirectoryPath, $generatorDefinition->getAttribute('output'));
			$xmlGenerator->generate($type, $outputPath, $settings);

			$id = $generatorDefinition->getAttribute('id');
			$files[$id] = $outputPath;
			$fileIdx++;
		}

		return $files;
	}


}
