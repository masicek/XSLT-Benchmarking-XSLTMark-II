<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once __DIR__ . '/IParamsDriver.php';
require_once __DIR__ . '/../../Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;


/**
 * Class for read params of test form XML file.
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
	private $test;

	/**
	 * The path of the directory with definition of test
	 *
	 * @var string
	 */
	private $rootDirectory;


	/**
	 * Set the params file.
	 *
	 * @param string $paramsFilePath The path of the file with deffinition of generated tests
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 */
	public function __construct($paramsFilePath)
	{
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
		$this->test = new \SimpleXMLElement($paramsFilePath, 0, TRUE);
	}


	/**
	 * Return the name of test
	 *
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->test['name'];
	}


	/**
	 * Return the path to the XSLT template
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return P::m($this->rootDirectory, (string)$this->test['template']);
	}


	/**
	 * Return the path to the XML files for testing
	 * - input
	 * - expected output
	 *
	 * @return array ([input] => [expected output])
	 */
	public function getCouplesPaths()
	{
		$couples = array();
		foreach ($this->test->couple as $couple)
		{
			$input = P::m($this->rootDirectory, (string)$couple['input']);
			$output = P::m($this->rootDirectory, (string)$couple['output']);

			$couples[$input] = $output;
		}

		return $couples;
	}


}
