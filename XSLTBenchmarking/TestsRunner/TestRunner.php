<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once ROOT . '/Microtime.php';
require_once ROOT . '/Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;


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
	 * Object for runnig XSLT transformation
	 *
	 * @var \XSLTBenchmarking\TestsRunner\Processor
	 */
	private $processor;

	/**
	 * List of processors selected for testing
	 *
	 * @var array
	 */
	private $processorsNames;

	/**
	 * Number of repeatig for each test and processor
	 *
	 * @var int
	 */
	private $repeating;

	/**
	 * Object for control that generated file is same as expected
	 *
	 * @var \XSLTBenchmarking\TestsRunner\Controlor
	 */
	private $controlor;

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
	 * @param \XSLTBenchmarking\TestsRunner\Processor $processor Class for parse one template in one processor
	 * @param array|TRUE $processorsSelected List of tested processors
	 * @param array $processors Exclude List of tested processors, that we want exclude form tested processors
	 * @param int $repeating Number of repeatig for each test and processor
	 * @param string $tmpDir Path of temporary directory
	 */
	public function __construct(
		\XSLTBenchmarking\Factory $factory,
		\XSLTBenchmarking\TestsRunner\Processor $processor,
		$processorsSelected,
		array $processorsExclude,
		$repeating,
		\XSLTBenchmarking\TestsRunner\Controlor $controlor,
		$tmpDir
	)
	{
		$tmpDir = P::mcd($tmpDir);

		$processorsAvailable = array_keys($processor->getAvailable());
		if ($processorsSelected === TRUE)
		{
			$processorsSelected = $processorsAvailable;
		}

		$processorsFinal = $processorsSelected;
		foreach ($processorsExclude as $processorExclude)
		{
			$key = array_search($processorExclude, $processorsFinal);
			if ($key !== FALSE)
			{
				unset($processorsFinal[$key]);
			}
		}
		$processorsFinal = array_values($processorsFinal);

		// check correct set of processors
		foreach ($processorsSelected as $processorSelected)
		{
			if (!in_array($processorSelected, $processorsAvailable))
			{
				throw new \XSLTBenchmarking\InvalidArgumentException('Unknown processor name "' . $processorSelected . '"');
			}
		}

		$this->factory = $factory;
		$this->processor = $processor;
		$this->processorsNames = $processorsFinal;
		$this->repeating = $repeating;
		$this->controlor = $controlor;
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
		$templatePath = $test->getTemplatePath();
		$couplesPaths = $test->getCouplesPaths();
		$report = $this->factory->getReport(
			$test->getName(),
			$templatePath
		);

		foreach ($this->processorsNames as $processorName)
		{
			foreach ($couplesPaths as $xmlInputPath => $expectedOutputPath)
			{
				// unique filename
				$pathInfo = pathinfo($expectedOutputPath);
				$microtime = \XSLTBenchmarking\Microtime::now();
				$microtime = str_replace('.', '-', $microtime);
				$filename = isset($pathInfo['filename']) ? ($pathInfo['filename'] . '-') : '';
				$extension = isset($pathInfo['extension']) ? ('.' . $pathInfo['extension']) : '';
				$filename = $filename . $microtime . $extension;
				$outputPath = P::m($this->tmpDir, $filename);

				// run transformations
				$result = $this->processor->run(
					$processorName,
					$templatePath,
					$xmlInputPath,
					$outputPath,
					$this->repeating
				);

				// set times and success
				if (is_array($result))
				{
					$success = TRUE;
					$spendTimes = $result;
				}
				else
				{
					$success = $result;
					$spendTimes = array();
				}

				// set correctness
				$correctness = FALSE;
				if ($success === TRUE)
				{
					$correctness = $this->controlor->isSame($outputPath, $expectedOutputPath);
				}

				// reported results
				$report->addRecord(
					$processorName,
					$xmlInputPath,
					$expectedOutputPath,
					$success,
					$correctness,
					$spendTimes
				);
			}
		}

		return $report;
	}



}
