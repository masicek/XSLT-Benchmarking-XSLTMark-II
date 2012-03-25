<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

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
	 * The path of the directory with definition of generated tests
	 *
	 * @var string
	 */
	private $rootDirectory;

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
	 * Object for generating XML files
	 *
	 * @var XmlGenerator
	 */
	private $xmlGenerator = NULL;


	/**
	 * Choose the params driver by extension
	 *
	 * @param \XSLTBenchmarking\TestsGenerator\XmlGenerator $xmlGenerator Object for generating XML files
	 * @param string $tmpDirectoryPath The path of the temporary directory
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct(
		\XSLTBenchmarking\TestsGenerator\XmlGenerator $xmlGenerator,
		$tmpDirectoryPath,
		$paramsFilePath)
	{
		P::cd($tmpDirectoryPath);
		P::cf($paramsFilePath);

		// validate
		$dom = new \DOMDocument();
		$dom->load($paramsFilePath);
		try {
			$dom->schemaValidate(P::m(__DIR__, 'XmlParamsDriver.xsd'));
		} catch (\Exception $e) {
			$error = libxml_get_last_error();
			throw new \XSLTBenchmarking\InvalidArgumentException(
				'File "' . $paramsFilePath . '" has wrong format: ' . $error->message
			);
		}

		$this->rootDirectory = dirname($paramsFilePath);
		$this->xmlGenerator = $xmlGenerator;
		$this->tmpDirectoryPath = $tmpDirectoryPath;
		$this->tests = new \SimpleXMLElement($paramsFilePath, 0, TRUE);
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
		return P::m($this->rootDirectory, (string)$this->tests['template']);
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
	 * Return the name of file with params of the test
	 *
	 * @param string $testName The name of the selected test
	 *
	 * @return string|NULL
	 */
	public function getTestParamsFileName($testName)
	{
		$test = $this->tests->xpath('//test[@name="' . $testName . '"]');
		$name = NULL;
		if (isset($test[0]['paramsFile']))
		{
			$name = (string)$test[0]['paramsFile'];
		}

		return $name;
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
			$files[(string)$file['id']] = P::m($this->rootDirectory, (string)$file);
		}

		// make tmp subdirectory for generating files
		if (count($this->tests->files->generated) > 0)
		{
			$tmpSubdirectory = strtolower(trim($this->getTemplateName()));
			$tmpSubdirectory = preg_replace('/[^a-z0-9-_]/', '-', $tmpSubdirectory);
			$tmpSubdirectory = preg_replace('/-+/', '-', $tmpSubdirectory);
			$tmpSubdirectoryPath = P::m($this->tmpDirectoryPath, $tmpSubdirectory);
			if (!is_dir($tmpSubdirectoryPath))
			{
				mkdir($tmpSubdirectoryPath);
			}
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
			$outputPath = P::m($this->tmpDirectoryPath, $tmpSubdirectory, (string)$generated['output']);
			$this->xmlGenerator->setDriver($type);
			$this->xmlGenerator->generate($outputPath, $this->rootDirectory, $settings);

			// add generated file into list of files
			$files[(string)$generated['id']] = $outputPath;
		}

		return $files;
	}


}
