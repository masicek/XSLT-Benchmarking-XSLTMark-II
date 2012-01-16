<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Reports;

require_once ROOT . '/Exceptions.php';
require_once ROOT . '/Microtime.php';

use XSLTBenchmarking\Microtime;

/**
 * Class for collect report information about one test
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Report
{


	/**
	 * Name of the reported test
	 *
	 * @var sring
	 */
	private $testName;

	/**
	 * Path of the XSLT template of the reported test
	 *
	 * @var string
	 */
	private $temaplatePath;

	/**
	 * List of reported values
	 *
	 * @var array
	 */
	private $records = array();


	/**
	 * Set common setting of the test for reporting
	 *
	 * @param string $testName Name of the reported test
	 * @param string $templatePath Path of the XSLT template of the reported test
	 */
	public function __construct($testName, $templatePath)
	{
		$this->testName = $testName;
		$this->temaplatePath = $templatePath;
	}


	/**
	 * Add report of one runnig transformation
	 *
	 * @param string $processorName Short name of selected processor
	 * @param string $xmlInputPath Path of XML input file
	 * @param string $expectedOutputPath Path of file with expected output
	 * @param string $success 'OK' or error message
	 * @param bool $correctness Flag of correcness transformation
	 * @param array $spendTimes Spended times by transformations
	 *
	 * @return void
	 */
	public function addRecord(
		$processorName,
		$xmlInputPath,
		$expectedOutputPath,
		$success,
		$correctness,
		array $spendTimes
	)
	{
		$record = array();

		$record['input'] = $xmlInputPath;
		$record['expectedOutput'] = $expectedOutputPath;
		$record['success'] = $success;
		$record['correctness'] = $correctness;
		if (count($spendTimes) > 0)
		{
			$record['sumTime'] = Microtime::sum($spendTimes);
			$record['avgTime'] = Microtime::divide($record['sumTime'], count($spendTimes));
		}
		else
		{
			$record['sumTime'] = '';
			$record['avgTime'] = '';
		}

		if (!isset($this->records[$processorName]))
		{
			$this->records[$processorName] = array();
		}

		$this->records[$processorName][] = $record;
	}


	/**
	 * Return name of the reported test
	 *
	 * @return string
	 */
	public function getTestName()
	{
		return $this->testName;
	}


	/**
	 * Retun path of the XSLT template of the reported test
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return $this->temaplatePath;
	}


	/**
	 * Return list of processors used in all reports
	 *
	 * @return array
	 */
	public function getProcessors()
	{
		return array_keys($this->records);
	}


	/**
	 * Return reports for the selected processor
	 *
	 * @param string $processorName Name of processor
	 *
	 * @return array
	 */
	public function getInputs($processorName)
	{
		if (!isset($this->records[$processorName]))
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('Unknown processor "' . $processorName . '"');
		}

		return $this->records[$processorName];
	}


}
