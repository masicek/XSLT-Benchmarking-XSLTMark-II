<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

/**
 * Class for run one test
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestRunner
{


	/**
	 * Factory class for making new objects
	 *
	 * @var \XSLTBenchmarking\Factory
	 */
	private $factory;

	/**
	 * List of processors selected for testing
	 *
	 * @var array
	 */
	private $processors;

	/**
	 * Number of repeatig for each test and processor
	 *
	 * @var int
	 */
	private $repeating;

	/**
	 * Path of temporary directory for generating output files by processors
	 *
	 * @var string
	 */
	private $tmpDir;


	/**
	 * Object configuration
	 *
	 * @param \XSLTBenchmarking\Factory $factory Factory class for making new objects
	 * @param array|TRUE $processors List of tested processors
	 * @param array $processors Exclude List of tested processors, that we want exclude form tested processors
	 * @param int $repeating Number of repeatig for each test and processor
	 * @param string $tmpDir
	 */
	public function __construct(
		\XSLTBenchmarking\Factory $factory,
		$processors,
		array $processorsExclude,
		$repeating,
		$tmpDir
	)
	{
		if ($processors === TRUE)
		{
			// TODO get all possible processors
			//$processors = ...
		}

		$processorsFinal = $processors;
		foreach ($processorsExclude as $processor)
		{
			$key = array_search($processor, $processorsFinal);
			if ($key !== FALSE)
			{
				unset($processorsFinal[$key]);
			}
		}
		$processorsFinal = array_values($processorsFinal);

		$this->factory = $factory;
		$this->processors = $processorsFinal;
		$this->repeating = $repeating;
		$this->tmpDir = $tmpDir;
	}


	/**
	 * Run one test
	 *
	 * @param \XSLTBenchmarking\TestsRunner\Test $test
	 *
	 * @return \XSLTBenchmarking\Reports\Report
	 */
	public function run(\XSLTBenchmarking\TestsRunner\Test $test)
	{
		// TODO co merit: speed, memory usage, correctness
		// - cas jednoho parsovani (pro dany procesor)
		// - jestli je vystup korektni nebo ne (pres Corrector)
		// - pouzita pamet
		//

		$report = $this->factory->getReport();
		// TODO setting values of report

		return $report;
	}


}
