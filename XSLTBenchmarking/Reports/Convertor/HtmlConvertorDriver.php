<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Reports;

require_once __DIR__ . '/IConvertorDriver.php';
require_once ROOT . '/Exceptions.php';
require_once ROOT . '/TestsGenerator/Templating/SmartyTemplatingDriver.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;
use XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver;

/**
 * Converting XML reports into HTML format
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class HtmlConvertorDriver implements IConvertorDriver
{


	/**
	 * Path of temporary directory
	 *
	 * @var string
	 */
	private $tmpDir;


	/**
	 * Configure object
	 *
	 * @param string $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir)
	{
		$this->tmpDir = P::mcd($tmpDir);
	}


	/**
	 * Convert reports into set format and save it into set directory.
	 *
	 * @param string $inputFile Report file for converting
	 * @param string $outputDir Directory to save generated file
	 *
	 * @return string
	 */
	public function convert($inputFile, $outputDir)
	{
		// output file name
		$inputFile = P::mcf($inputFile);
		$name = pathinfo($inputFile, PATHINFO_FILENAME) . '.html';
		$outputFile = P::m(P::mcd($outputDir), $name);

		$report = $this->getReport($inputFile);

		// list of processors
		$processorsList = array();
		$processorsFullNames = array();
		foreach ($report->xpath('//global/processors/processor') as $processor)
		{
			$name = (string)$processor['name'];
			unset($processor['name']);

			$processorsFullNames[$name] = (string)$processor['fullName'];
			unset($processor['fullName']);

			$processorsList[$name] = $this->getAttributes($processor);
		}

		// list of tests
		$tests = array();
		foreach ($report->xpath('//tests/test') as $test)
		{
			$processors = array();
			foreach ($test->xpath('processor') as $processor)
			{
				$inputs = array();
				foreach ($processor->xpath('input') as $input)
				{
					$inputs[] = $this->getAttributes($input);
				}

				$processorName = (string)$processor['name'];
				$processors[$processorName] = $inputs;
			}

			$testName = (string)$test['name'];
			$tests[$testName]['template'] = (string)$test['template'];
			$tests[$testName]['processors'] = $processors;
		}

		// settings
		$settings = array();
		$settings['processors'] = $processorsList;
		$settings['processorsFullNames'] = $processorsFullNames;
		$settings['tests'] = $tests;

		$templating = new SmartyTemplatingDriver($this->tmpDir);
		$templating->generate(P::m(__DIR__, 'report.tpl.html'), $outputFile, $settings);

		return $outputFile;
	}


	/**
	 * Load report file into \SimpleXML object.
	 *
	 * @param string $path Path of report file for reading
	 *
	 * @return \SimpleXMLElement
	 */
	private function getReport($path)
	{
		// validate
		$dom = new \DOMDocument();
		$dom->load($path);
		try {
			$dom->schemaValidate(P::m(__DIR__, '/../Report.xsd'));
		} catch (\Exception $e) {
			$error = libxml_get_last_error();
			throw new \XSLTBenchmarking\InvalidArgumentException(
				'File "' . $path . '" has wrong format: ' . $error->message
			);
		}

		// make SimpleXML
		$report = new \SimpleXMLElement($path, 0, TRUE);

		return $report;
	}


	/**
	 * Return attributes of element as array
	 *
	 * @param \SimpleXMLElement $element
	 *
	 * @return array array([ATTRIBUTE] => [VALUE], ...)
	 */
	private function getAttributes(\SimpleXMLElement $element)
	{
		$attributes = array();
		foreach ($element->attributes() as $attribute => $value)
		{
			$attributes[(string)$attribute] = (string)$value;
		}
		return $attributes;
	}


}
