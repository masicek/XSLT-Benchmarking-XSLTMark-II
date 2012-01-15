<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Reports;

require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;


/**
 * Printing reports of testing into one XML file
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Printer
{


	/**
	 * Directory for generating the report
	 *
	 * @var string
	 */
	private $reportsDir;

	/**
	 * ([name] => ('fullName' => [fullName], 'link' => [link], 'verions' => [version]), ...)
	 *
	 * @var array
	 */
	private $processors;

	/**
	 * Number of repeating each tranformation during testing
	 *
	 * @var int
	 */
	private $repeating;

	/**
	 * List of reports
	 *
	 * @var array of \XSLTBenchmarking\Reports\Report
	 */
	private $reports = array();


	/**
	 * Setting common infromation for report
	 *
	 * @param string $reportsDir Directory for generating the report
	 * @param array $processors ([name] => ('fullName' => [fullName], 'link' => [link], 'verions' => [version]), ...)
	 * @param int $repeating Number of repeating each tranformation during testing
	 */
	public function __construct($reportsDir, array $processors, $repeating)
	{
		P::mcd($reportsDir);

		$this->reportsDir = $reportsDir;
		$this->processors = $processors;
		$this->repeating = $repeating;
	}


	/**
	 * Add one report of one test into list of all reported tests
	 *
	 * @param \XSLTBenchmarking\Reports\Report $report Report of one test
	 *
	 * @return void
	 */
	public function addReport(\XSLTBenchmarking\Reports\Report $report)
	{
		$this->reports[] = $report;
	}


	/**
	 * Print all reports into one XML file and return path of the file
	 *
	 * Template of XML output:
	 * <pre>
	 * <reports>
	 *    <global>
	 *       <repeating>...</repeating>
	 *       <processors>
	 *          <processor name="..." fullName="..." link="..." version="..." />
	 *          <processor ... />
	 *          ...
	 *       </processors>
	 *    </global>
	 *    <tests>
	 *       <test name="..." template="...">
	 *          <processor name="...">
	 *             <input input="..." expectedOutput="..." success="..." corretness="..." sumTime="..." avgTime="..." />
	 *             <input ... />
	 *             ...
	 *          </processor>
	 *          <processor ...>
	 *             ...
	 *          </processor>
	 *          ...
	 *       </test>
	 *       <test ...>
	 *          ...
	 *       </test>
	 *       ...
	 *    </tests>
	 * </reports>
	 * </pre>
	 *
	 * @return string
	 */
	public function printAll()
	{
		// initialize xml
		$reportsEl = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><reports></reports>');

		// global informations
		$globalEl = $reportsEl->addChild('global');
		$globalEl->addChild('repeating', $this->repeating);
		$processorsListEl = $globalEl->addChild('processors');
		foreach ($this->processors as $name => $processor)
		{
			$processorListEl = $processorsListEl->addChild('processor');
			$processorListEl->addAttribute('name', $name);
			$processorListEl->addAttribute('fullName', $processor['fullName']);
			$processorListEl->addAttribute('link', $processor['link']);
			$processorListEl->addAttribute('version', $processor['version']);
		}

		// reports of tests
		$testsEl = $reportsEl->addChild('tests');
		foreach ($this->reports as $report)
		{
			$testEl = $testsEl->addChild('test');
			$testEl->addAttribute('name', $report->getTestName());
			$testEl->addAttribute('template', $report->getTemplatePath());

			foreach ($report->getProcessors() as $processorName)
			{
				$processorEl = $testEl->addChild('processor');
				$processorEl->addAttribute('name', $processorName);

				foreach ($report->getInputs($processorName) as $data)
				{
					$inputEl = $processorEl->addChild('input');
					$inputEl->addAttribute('input', $data['input']);
					$inputEl->addAttribute('expectedOutput', $data['expectedOutput']);
					$inputEl->addAttribute('success', $data['success']);
					$inputEl->addAttribute('corretness', (int)$data['corretness']);
					$inputEl->addAttribute('sumTime', $data['sumTime']);
					$inputEl->addAttribute('avgTime', $data['avgTime']);
				}
			}
		}

		// print unique file
		$reportFilePath = P::m($this->reportsDir, date('Y-m-d-H-i-s') . '.xml');

		// save + make indent
		$dom = dom_import_simplexml($reportsEl)->ownerDocument;
		$dom->formatOutput = TRUE;
		$dom->save($reportFilePath);

		return $reportFilePath;
	}


}
