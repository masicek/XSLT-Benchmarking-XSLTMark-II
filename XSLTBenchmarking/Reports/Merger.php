<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Reports;

require_once ROOT . '/Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Merging of more reported tests into one XML file.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Merger
{


	/**
	 * List of reports for generating
	 *
	 * @var arry of \SimpleXML
	 */
	private $reports;


	/**
	 * Make merge of all added reports, save result into file and
	 * return path of generated file
	 *
	 * @param string $outputDirPath Path of directory for generating mergered report
	 *
	 * @return string
	 */
	public function merge($outputDirPath)
	{
		// make name of output
		P::cd($outputDirPath);
		$outputPath = P::m($outputDirPath, date('Y-m-d-H-i-s'). '-merge' . '.xml');

		$mergeredReports = $this->getMergeredReport();
		$this->saveMegeredReports($outputPath, $mergeredReports);

		return $outputPath;
	}


	/**
	 * Add report into list of mergered reports
	 *
	 * @param string $path Path of XML file with reports
	 *
	 * @throws \XSLTBenchmarking\InvalidArgumentException Wrong format of file with params
	 * @return void
	 */
	public function addReportFile($path)
	{
		$path = P::mcf($path);

		// validate
		$dom = new \DOMDocument();
		$dom->load($path);
		try {
			$dom->schemaValidate(P::m(__DIR__, 'Report.xsd'));
		} catch (\Exception $e) {
			$error = libxml_get_last_error();
			throw new \XSLTBenchmarking\InvalidArgumentException(
				'File "' . $path . '" has wrong format: ' . $error->message
			);
		}

		// make SimpleXML
		$report = new \SimpleXMLElement($path, 0, TRUE);

		$this->reports[] = $report;
	}


	/**
	 * Merge reports into one SimpleXML object.
	 *
	 * @return \SimpleXMLElement
	 */
	private function getMergeredReport()
	{
		$mergeEl = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><reports></reports>');

		// skeleton
		$globalEl = $mergeEl->addChild('global');
		$processorsListEl = $globalEl->addChild('processors');
		$testsListEl = $mergeEl->addChild('tests');

		foreach ($this->reports as $report)
		{
			// processors
			$processors = $report->xpath('//global/processors');
			$this->copyChilds($processors[0], $processorsListEl, array('processor|name'));

			// tests
			$tests = $report->xpath('//tests');
			$this->copyChilds($tests[0], $testsListEl, array('test|name', 'processor|name', 'input|input|expectedOutput'));
		}

		return $mergeEl;
	}


	/**
	 * Copy children elements from source element into destination element.
	 * Runction is called recursively if $childrenInfos have more than one value.
	 *
	 * @param \SimpleXMLElement $sourceElement Source XML element
	 * @param \SimpleXMLElement $destinationElement Destionation XML element
	 * @param array $childrenInfos Information for copy.
	 * Each value is name of copied element and atributes for comapring (separated by '|').
	 *
	 * @return void
	 */
	private function copyChilds(
		\SimpleXMLElement $sourceElement,
		\SimpleXMLElement $destinationElement,
		array $childrenInfos = array()
	)
	{
		$childInfo = array_shift($childrenInfos);
		$infos = explode('|', $childInfo);
		$childName = array_shift($infos);

		$childrenElements = $sourceElement->xpath($childName);
		foreach ($childrenElements as $childElement)
		{
			$checkRule = array();
			foreach ($infos as $info)
			{
				$checkRule[] = '@' . $info . '="' . (string)$childElement[$info] . '"';
			}
			if ($checkRule)
			{
				$checkRule = '[' . implode(' and ', $checkRule) . ']';
			}

			$destionationChildElements = $destinationElement->xpath($childName . $checkRule);
			if (count($destionationChildElements) == 0)
			{
				$addedElement = $destinationElement->addChild($childName);
			}
			else
			{
				$addedElement = $destionationChildElements[0];
			}

			// copy attribute - newer values are preferred
			$this->copyOrReplaceAttributes($childElement, $addedElement);

			// copy children
			if (count($childrenInfos) > 0)
			{
				$this->copyChilds($childElement, $addedElement, $childrenInfos);
			}
		}
	}


	/**
	 * Copy element from source element into destination element.
	 * If attribute existed in destination element, than its value is replaced by newer.
	 *
	 * @param \SimpleXMLElement $sourceElement Source XML element
	 * @param \SimpleXMLElement $destinationElement Destionation XML element
	 *
	 * @return void
	 */
	private function copyOrReplaceAttributes(\SimpleXMLElement $sourceElement, \SimpleXMLElement $destinationElement)
	{
		foreach ($sourceElement->attributes() as $name => $value)
		{
			$destinationElement[$name] = $value;
		}
	}


	/**
	 * Save mergered reports into file. XML is indented.
	 *
	 * @param sring $outputPath Path of file
	 * @param \SimpleXMLElement $mergeredReports Report for saving
	 *
	 * @return void
	 */
	private function saveMegeredReports($outputPath, \SimpleXMLElement $mergeredReports)
	{
		// save + make indent
		$dom = dom_import_simplexml($mergeredReports)->ownerDocument;
		$dom->formatOutput = TRUE;
		$dom->save($outputPath);
	}


}
