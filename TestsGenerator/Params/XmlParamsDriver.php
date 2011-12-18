<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once __DIR__ . '/IParamsDriver.php';
require_once __DIR__ . '/../XmlGenerator/XmlGenerator.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

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
	 * @var \SimpleXMLElement
	 */
	private $tests;

	/**
	 * The path of the file with deffinition of generated tests
	 *
	 * @var string
	 */
	private $paramsFilePath;

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
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 */
	public function __construct($paramsFilePath, $tmpDirectoryPath)
	{
		$this->paramsFilePath = $paramsFilePath;
		$this->tmpDirectoryPath = $tmpDirectoryPath;
		$this->tests = new \SimpleXMLElement($paramsFilePath, 0, TRUE);
		// TODO add control of XML by DTD or XSD
	}


	/**
	 * Return the name of tests collection
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return (string)$this->tests['name'];
	}


	/**
	 * Return the path to the template file
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return P::m(dirname($this->paramsFilePath), (string)$this->tests['template']);
	}


	/**
	 * Return the type of templating
	 *
	 * @return string
	 */
	public function getTemplatingType()
	{
		return (string)$this->tests['templatingType'];
	}


	/**
	 * Return the list of tests names
	 *
	 * @return array
	 */
	public function getTestsNames()
	{
		$names = array();

		foreach ($this->tests->test as $test)
		{
			$names[] = (string)$test['name'];
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
		$test = $this->tests->xpath('//test[@name="' . $testName . '"]');
		$allFiles = $this->getAllFilesPaths();

		foreach ($test[0]->file as $file)
		{
			$input = $allFiles[(string)$file['input']];
			$output = $allFiles[(string)$file['output']];
			$files[$input] = $output;
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

		$test = $this->tests->xpath('//test[@name="' . $testName . '"]');
		foreach ($test[0]->setting as $setting)
		{
			$settings[(string)$setting['name']] = (string)$setting;
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

		// existed xml files
		foreach ($this->tests->files->file as $file)
		{
			$files[(string)$file['id']] = P::m(dirname($this->paramsFilePath), (string)$file);
		}

		// generated xml files
		foreach ($this->tests->files->generated as $generated)
		{
			// read settings
			$settings = array();
			foreach ($generated->setting as $setting)
			{
				$settings[(string)$setting['name']] = (string)$setting;
			}

			// generate file into TMP
			$type = (string)$generated['generator'];
			$outputPath = P::m($this->tmpDirectoryPath, (string)$generated['output']);
			$xmlGenerator = new XmlGenerator($type);
			$xmlGenerator->generate($outputPath, $settings);

			// add generated file into list of files
			$files[(string)$generated['id']] = $outputPath;
		}

		return $files;
	}


}
