<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/Directory.php';


/**
 * Container for information about one test.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Test
{

	/**
	 * The human-redable name of the test
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The path of the test template for generating the test
	 *
	 * @var string
	 */
	private $templatePath;

	/**
	 * Type of templating for generating test from template
	 *
	 * @var string
	 */
	private $templatingType;

	/**
	 * The path of the test directory for generating
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The array of settings for the test teamplte
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * The list of paths of input xml file for testing
	 *
	 * @var array
	 */
	private $xmlFilesPaths = array();


	/**
	 * Set the human-redable name of the test
	 *
	 * @param string $name The human-redable name of the test
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}


	/**
	 * Set the path of the test template
	 *
	 * @param string $templatePath The path of the test template
	 *
	 * @return void
	 */
	public function setTemplatePath($templatePath)
	{
		$this->templatePath = $templatePath;
	}


	/**
	 * Return the path of the test template
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return $this->templatePath;
	}


	/**
	 * Set the type of templating
	 *
	 * @param string $templatingType The type of templating
	 *
	 * @return void
	 */
	public function setTemplatingType($templatingType)
	{
		$this->templatingType = $templatingType;
	}


	/**
	 * Return the type of templating
	 *
	 * @return string
	 */
	public function getTemplatingType()
	{
		return $this->templatingType;
	}


	/**
	 * Set the path to the test
	 *
	 * @param string $rootPath The root directory of all tests
	 *
	 * @return void
	 */
	public function setPath($rootPath)
	{
		$name = $this->name;
		$name = strtolower(trim($name));
		$name = preg_replace('/[^a-z0-9-_]/', '-', $name);
		$name = preg_replace('/-+/', '-', $name);

		$this->path = Directory::make($rootPath, $name);
	}


	/**
	 * Return the path of directory for generating the test
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}


	/**
	 * Return the path of the test xslt file
	 *
	 * @return string
	 */
	public function getXsltPath()
	{
		preg_match('/[^\/\\\\]*[.]tpl[.]xslt$/', $this->getTemplatePath(), $match);
		$xsltName = preg_replace('/[.]tpl[.]xslt/', '.xslt', $match[0]);

		return Directory::make($this->getPath(), $xsltName);
	}


	/**
	 * Add the setting of the test template for generating
	 *
	 * @param string $name The name of the setting
	 * @param string $value The value of the setting
	 *
	 * @return void
	 */
	public function addSetting($name, $value)
	{
		$this->settings[$name] = $value;
	}


	/**
	 * Add settins of the test for generating
	 *
	 * @param array $settings ([name] => [value], ...)
	 *
	 * @return void
	 */
	public function addSettings(array $settings)
	{
		$this->settings = array_merge($this->settings, $settings);
	}


	/**
	 * Return all settings of test for generating
	 *
	 * @return array
	 */
	public function getSettings()
	{
		return $this->settings;
	}


	/**
	 * Add the path of xml file for testing
	 *
	 * @param string $filePath The path of file
	 *
	 * @return void
	 */
	public function addXmlFilePath($filePath)
	{
		$this->xmlFilesPaths[] = $filePath;
	}


	/**
	 * Add paths of xml files for testing
	 *
	 * @param array $filePaths ([file], ...)
	 *
	 * @return void
	 */
	public function addXmlFilesPaths(array $filesPaths)
	{
		$this->xmlFilesPaths = $this->xmlFilesPaths + $filesPaths;
	}


	/**
	 * Return all paths of xml files for testing
	 *
	 * @return array
	 */
	public function getXmlFilesPaths()
	{
		return $this->xmlFilesPaths;
	}


}
