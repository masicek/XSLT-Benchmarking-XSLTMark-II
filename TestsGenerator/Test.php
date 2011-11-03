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
	 * The path of the test directory for generating
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The array of variables for setting the test teamplte
	 *
	 * @var array
	 */
	private $variables = array();

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
	 * Set the path to the test
	 *
	 * @param string $rootPath The root directory of all tests
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
	 * Add the variable of the test template for generating
	 *
	 * @param string $name The name of the variable
	 * @param string $value The value of the variable
	 */
	public function addVariable($name, $value)
	{
		$this->variables[$name] = $value;
	}


	/**
	 * Add variables of the test for generating
	 *
	 * @param array $variables ([name] => [value], ...)
	 */
	public function addVariables(array $variables)
	{
		$this->variables = array_merge($this->variables, $variables);
	}


	/**
	 * Return all variables of test for generating
	 *
	 * @return array
	 */
	public function getVariables()
	{
		return $this->variables;
	}


	/**
	 * Add the path of xml file for testing
	 *
	 * @param string $filePath The path of file
	 */
	public function addXmlFilePath($filePath)
	{
		$this->xmlFilesPaths[] = $filePath;
	}


	/**
	 * Add paths of xml files for testing
	 *
	 * @param array $filePaths ([file], ...)
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
