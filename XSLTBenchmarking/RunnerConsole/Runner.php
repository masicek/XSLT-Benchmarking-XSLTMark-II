<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\RunnerConsole;

require_once LIBS . '/PhpOptions/PhpOptions.min.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

require_once ROOT . '/Factory.php';
require_once ROOT . '/Microtime.php';
require_once ROOT . '/Printer.php';

require_once ROOT . '/TestsGenerator/Generator.php';
require_once ROOT . '/TestsGenerator/Templating/Templating.php';
require_once ROOT . '/TestsGenerator/Params/Params.php';
require_once ROOT . '/TestsGenerator/XmlGenerator/XmlGenerator.php';

require_once ROOT . '/TestsRunner/Runner.php';
require_once ROOT . '/TestsRunner/Params/Params.php';
require_once ROOT . '/TestsRunner/TestRunner.php';
require_once ROOT . '/TestsRunner/Processors/Processor.php';
require_once ROOT . '/TestsRunner/Controlor.php';

require_once ROOT . '/Reports/Printer.php';
require_once ROOT . '/Reports/Merger.php';


use XSLTBenchmarking\Microtime;
use XSLTBenchmarking\Printer;
use PhpOptions\Options;
use PhpOptions\Option;
use PhpPath\P;

/**
 * Class for runnig XSLT Benchmark from console
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Runner
{


	/**
	 * Comman line options
	 *
	 * @var \PhpOptions\Options
	 */
	private $options;


	/**
	 * Factory class for making new objects
	 *
	 * @var \XSLTBenchmarking\Factory
	 */
	private $factory;


	// ---- RUNNING ----

	/**
	 * Setting the object.
	 *
	 * @param string $baseDir Base dir of expected set directories
	 */
	public function __construct($baseDir)
	{
		$this->options = $this->defineOptions($baseDir);
		$this->factory = new \XSLTBenchmarking\Factory();
	}


	/**
	 * Run XSLT Benchmarking
	 * - show help
	 * - generate tests
	 * @todo - run tests
	 * @todo - print reports
	 *
	 * @return void
	 */
	public function run()
	{
		$options = $this->options;

		if ($options->get('Help'))
		{// @codeCoverageIgnoreStart
			fwrite(STDOUT, $options->getHelp());
			return;
		}// @codeCoverageIgnoreEnd

		if ($options->get('Processors available'))
		{// @codeCoverageIgnoreStart
			$this->printAvailableProcessors();
			return;
		}// @codeCoverageIgnoreEnd

		// generating tests
		if ($options->get('Generate'))
		{
			$this->generateTests();
		}

		// run tests
		if ($options->get('Run'))
		{
			$this->runTests();
		}

		// merge reports
		if ($options->get('Merge reports'))
		{
			$this->mergeReports();
		}
	}


	// ---- DEFINE COMMAND-LINE OPTIONS ----


	/**
	 * Define excepted command-line options.
	 *
	 * @param string $baseDir Base dir of expected set directories
	 *
	 * @return \PhpOptions\Options
	 */
	private function defineOptions($baseDir)
	{
		try {
			$options = new Options();

			// base settings of options
			$help = Option::make('Help')->description('Show this help');
			$options->add($help)->defaults('Help');

			$description = 'XSLT Benchmarking ' . VERSION . ' - Console Runner' . PHP_EOL;
			$description .= 'author: Viktor Masicek <viktor@masicek.net>';
			$options->description($description);

			$optionsList = array();

			// common
			$optionsList[] = Option::make('Verbose')->description('Print informations during running scrips');

			// directories
			$templates = $optionsList[] = Option::directory('Templates', $baseDir)
				->short()
				->value(FALSE)
				->defaults('../Data/TestsTemplates')
				->description('Directory containing templates for generating tests');
			$tests = $optionsList[] = Option::directory('Tests', $baseDir, 'makeDir')
				->short()
				->value(FALSE)
				->defaults('../Data/Tests')
				->description('Directory for generating tests');
			$reportsDir = $optionsList[] = Option::directory('Reports', $baseDir, 'makeDir')
				->short()
				->value(FALSE)
				->defaults('../Data/Reports')
				->description('Directory for reports of tests');
			$optionsList[] = Option::directory('Tmp', $baseDir, 'makeDir')
				->short()
				->value(FALSE)
				->defaults('../Tmp')
				->description('Temporary directory');


			// generating tests
			$optionsList[] = Option::make('Generate')->description('Generating tests from templates');
			// @HACK in PhpOption 2.0.0 use array of dirs
			$optionsList[] = Option::series('Templates dirs', ',')
				->short()
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'Subdirectories of director set by "' . $templates->getOptions() . '" ' .
					'containing tests templates for generating, separated by character ",". ' .
					'If this option is not set (or is set without value), ' .
					'then all tests templates are selected ' .
					'(all subdirectories are considered as tests templates).'
				);


			// run tests
			$optionsList[] = Option::make('Run')->description('Run prepared tests');
			// @HACK in PhpOption 2.0.0 use array of dirs
			$optionsList[] = Option::series('Tests dirs', ',')
				->short()
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'Subdirectories of director set by "' . $tests->getOptions() . '" ' .
					'containing tests for runnig, separated by character ",". ' .
					'If this option is not set (or is set without value), ' .
					'then all tests are selected ' .
					'(all subdirectories are considered as tests).'
				);

			// @HACK in PhpOption 2.0.0 use array of enum
			$processors = $optionsList[] = Option::series('Processors', ',')
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'List of tested processors. ' .
					'If this option is not set (or is set without value), ' .
					'then all available processors are tested.'
				);

			$processorsExclude = $optionsList[] = Option::series('Processors exclude', ',')
				->short('e')
				->defaults(array())
				->description(
					'List of tested processors, that we want exclude form tested processors.'
				);

			$optionsList[] = Option::make('Processors available')
				->short('a')
				->description(
					'Print list of short names of available processors (possible used in options "' . $processors->getOptions() . '" ' .
					'and "' . $processorsExclude->getOptions() . '") and their kernels and full names.'
				);

			$optionsList[] = Option::integer('Repeating', 'unsigned')
				->short()
				->defaults(1)
				->description('Number of repeatig for each test and processor.');

			// merge reports
			$orderReports = $optionsList[] = Option::enum('Order reports', 'asc,desc,set')
				->defaults('set')
				->description('Type of ordering for merge reports.');

			$optionsList[] = Option::series('Merge reports')
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'List of mergered reports in directory set by "' . $reportsDir->getOptions() . '". ' .
					'If this option is set without value, ' .
					'then all available reports (without suffix "-merge") are mergered. ' .
					'Reports are mergered in set order or ordered by name ' .
					'if option "' . $orderReports->getOptions() . '" is set.'
				);

			$options->add($optionsList);

			// dependences + groups
			$options->dependences('Generate', array(
					'Templates',
					'Templates dirs',
					'Tests',
					'Tmp'),
				'Generating tests'
			);

			$options->dependences('Run', array(
					'Tests',
					'Tests dirs',
					'Processors',
					'Processors exclude',
					'Repeating',
					'Reports',
					'Tmp')
			);
			$options->group('Runnig tests', array(
					'Run',
					'Tests',
					'Tests dirs',
					'Processors',
					'Processors exclude',
					'Processors available',
					'Repeating',
					'Reports',
					'Tmp')
			);

			$options->group('Reporting', array(
				'Merge reports',
				'Order reports',
				'Reports',
			));

		} catch (\PhpOptions\UserBadCallException $e) {// @codeCoverageIgnoreStart
			Printer::info('ERROR: ' . $e->getMessage());
			die();
		}// @codeCoverageIgnoreEnd

		return $options;
	}


	// ---- PARTS OF RUNNING ----


	/**
	 * Print available processors
	 *
	 * @codeCoverageIgnore
	 *
	 * @return void
	 */
	private function printAvailableProcessors()
	{
		$tmpDir = $this->options->get('Tmp');
		$processor = new \XSLTBenchmarking\TestsRunner\Processor($tmpDir);
		$processorsDrivers = $processor->getAvailable();

		// get max lengtho of each parts
		$maxName = 0;
		$maxKernel = 0;
		foreach($processorsDrivers as $driverName => $processorDriver)
		{
			if (strlen($driverName) > $maxName)
			{
				$maxName = strlen($driverName);
			}
			if (strlen($processorDriver->getKernel()) > $maxKernel)
			{
				$maxKernel = strlen($processorDriver->getKernel());
			}
		}

		// print list
		Printer::header('Available processors');
		$name = str_pad('SHORT NAME', $maxName, ' ', STR_PAD_LEFT);
		$kernel = str_pad('KERNEL', $maxKernel, ' ', STR_PAD_LEFT);
		Printer::header($name . ' | ' . $kernel . ' | FULL NAME');
		foreach($processorsDrivers as $driverName => $processorDriver)
		{
			$name = str_pad($driverName, $maxName, ' ', STR_PAD_LEFT);
			$kernel = str_pad($processorDriver->getKernel(), $maxKernel, ' ', STR_PAD_LEFT);
			Printer::info($name . ' | ' . $kernel . ' | ' . $processorDriver->getFullName());
		}
	}


	/**
	 * Generate tests from tests templates
	 *
	 * @return void
	 */
	private function generateTests()
	{
		Printer::header('Generate Tests');

		$options = $this->options;
		$templatesDir = $options->get('Templates');
		$testsDir = $options->get('Tests');
		$tmpDir = $options->get('Tmp');

		$generator = new \XSLTBenchmarking\TestsGenerator\Generator(
			$this->factory,
			new \XSLTBenchmarking\TestsGenerator\Params(
				new \XSLTBenchmarking\TestsGenerator\XmlGenerator(),
				$tmpDir),
			new \XSLTBenchmarking\TestsGenerator\Templating($tmpDir),
			new \XSLTBenchmarking\TestsRunner\Params(),
			$templatesDir,
			$testsDir
		);

		$templatesDirs = $options->get('Templates dirs');

		// generate all templates
		if ($templatesDirs === TRUE)
		{
			$templatesDirs = $this->getSubresources($templatesDir, 'directories');
		}

		foreach ($templatesDirs as $templateDir)
		{
			$generator->addTests($templateDir);
		}

		$start = Microtime::now();
		$testsNumber = $generator->generateAll($options->get('Verbose'));
		$end = Microtime::now();
		$length = Microtime::substract($end, $start);
		$length = Microtime::humanReadable($length);

		Printer::info('Tests generating lasted "' . $length . '". ' . $testsNumber . ' tests were generated from ' . count($templatesDirs) . ' temapltes into directory "' . $testsDir . '".');
	}


	/**
	 * Run all tests
	 *
	 * @return void
	 */
	private function runTests()
	{
		Printer::header('Run Tests');

		$options = $this->options;
		$testsDir = $options->get('Tests');
		$reportsDir = $options->get('Reports');
		$processors = $options->get('Processors');
		$processorsExclude = $options->get('Processors exclude');
		$repeating = $options->get('Repeating');
		$tmpDir = $options->get('Tmp');

		$processor = new \XSLTBenchmarking\TestsRunner\Processor($tmpDir);
		$runner = new \XSLTBenchmarking\TestsRunner\Runner(
			$this->factory,
			new \XSLTBenchmarking\TestsRunner\Params(),
			new \XSLTBenchmarking\TestsRunner\TestRunner(
				$this->factory,
				$processor,
				$processors,
				$processorsExclude,
				$repeating,
				new \XSLTBenchmarking\TestsRunner\Controlor(),
				$tmpDir
			),
			new \XSLTBenchmarking\Reports\Printer(
				$reportsDir,
				$processor->getInformations()
			),
			$testsDir
		);

		$testsDirs = $options->get('Tests dirs');

		// generate all templates
		if ($testsDirs === TRUE)
		{
			$testsDirs = $this->getSubresources($testsDir, 'directories');
		}

		foreach ($testsDirs as $testDir)
		{
			$runner->addTest($testDir);
		}

		$start = Microtime::now();
		$reportFilePath = $runner->runAll($options->get('Verbose'));
		$end = Microtime::now();
		$length = Microtime::substract($end, $start);
		$length = Microtime::humanReadable($length);

		Printer::info('Tests runnig lasted "' . $length . '". Reports of tests are in "' . $reportFilePath . '".');
	}


	/**
	 * Merge reports into one report file
	 *
	 * @return void
	 */
	private function mergeReports()
	{
		Printer::header('Merge reports');

		$options = $this->options;
		$reportsDir = $options->get('Reports');
		$reportsFiles = $options->get('Merge reports');
		$orderType = $options->get('Order reports');

		// generate all templates
		if ($reportsFiles === TRUE)
		{
			$reportsFiles = $this->getSubresources($reportsDir, 'files');
		}

		// order
		if ($orderType == 'asc' || $orderType == 'desc')
		{
			sort($reportsFiles);
		}
		if ($orderType == 'desc')
		{
			array_reverse($reportsFiles);
		}

		$merger = new \XSLTBenchmarking\Reports\Merger();

		foreach ($reportsFiles as $reportFile)
		{
			$merger->addReportFile(P::m($reportsDir, $reportFile));
		}

		$generatedReport = $merger->merge($reportsDir);

		Printer::info(count($reportsFiles) . ' resports were mergered into "' . $generatedReport . '".');
	}


	// ---- HELPS FUNCTIONS ----


	/**
	 * Return subdirectories and files names in set path
	 *
	 * @param string $path Directory that is scanned
	 *
	 * @return array
	 */
	private function getSubresources($path, $type = NULL)
	{
		$allResources = scandir($path);

		$dirs = array();
		foreach ($allResources as $resource)
		{
			if (!in_array($resource, array('.', '..')) && is_dir(P::m($path, $resource)))
			{
				if ($type == 'directories' && !is_dir($resource))
				{
					continue;
				}
				if ($type == 'files' && !is_file($resource))
				{
					continue;
				}
				$dirs[] = $resource;
			}
		}

		return $dirs;
	}


}
