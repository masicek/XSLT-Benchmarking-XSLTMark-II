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

require_once ROOT . '/TestsGenerator/Generator.php';
require_once ROOT . '/TestsGenerator/Templating/Templating.php';
require_once ROOT . '/TestsGenerator/Params/Params.php';
require_once ROOT . '/TestsGenerator/XmlGenerator/XmlGenerator.php';

require_once ROOT . '/TestsRunner/Runner.php';
require_once ROOT . '/TestsRunner/Params/Params.php';
require_once ROOT . '/TestsRunner/TestRunner.php';
require_once ROOT . '/TestsRunner/Processor/Processor.php';

require_once ROOT . '/Reports/Printer.php';


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
			// @HACK PHP_EOL will not be used in PhpOptions 2.0.0 in options descriptions

			$options = new Options();

			// base settings of options
			$help = Option::make('Help')->description('Show this help');
			$options->add($help)->defaults('Help');

			$description = 'XSLT Benchmarking ' . VERSION . ' - Console Runner' . PHP_EOL;
			$description .= 'author: Viktor Masicek <viktor@masicek.net>';
			$options->description($description);

			$optionsList = array();


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
			$optionsList[] = Option::directory('Reports', $baseDir, 'makeDir')
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
					'Subdirectories of director set by "' . $templates->getOptions() . '"' . PHP_EOL .
					'containing tests templates for generating, separated by character ",".' . PHP_EOL .
					'If this option is not set (or is set without value),' . PHP_EOL .
					'then all tests templates are selected' . PHP_EOL .
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
					'Subdirectories of director set by "' . $tests->getOptions() . '"' . PHP_EOL .
					'containing tests for runnig, separated by character ",".' . PHP_EOL .
					'If this option is not set (or is set without value),' . PHP_EOL .
					'then all tests are selected' . PHP_EOL .
					'(all subdirectories are considered as tests).'
				);

			// @HACK in PhpOption 2.0.0 use array of enum
			$optionsList[] = Option::series('Processors', ',')
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'List of tested processors' . PHP_EOL .
					'If this option is not set (or is set without value),' . PHP_EOL .
					'then all available processors are tested.'
				);

			$optionsList[] = Option::series('Processors exclude', ',')
				->short('e')
				->description(
					'List of tested processors, that we want exclude form tested processors.'
				);

			$optionsList[] = Option::integer('Repeating', 'unsigned')
				->short()
				//->defaults(1) @HACK supported in PhpOptions 2.0.0
				->description('Number of repeatig for each test and processor.');


			$options->add($optionsList);

			// dependences + groups
			// @HACK in PhpOptions 2.0.0 generate groups based on dependences
			$options->dependences('Generate', array(
				'Templates',
				'Templates dirs',
				'Tests',
				'Tmp'
			));
			$options->group('Generating tests', array('Generate',
				'Templates',
				'Templates dirs',
				'Tests',
				'Tmp'
			));

			$options->dependences('Run', array(
				'Tests',
				'Tests dirs',
				'Processors',
				'Processors exclude',
				'Repeating',
				'Reports',
				'Tmp'
			));
			$options->group('Runnig tests', array('Run',
				'Tests',
				'Tests dirs',
				'Processors',
				'Processors exclude',
				'Repeating',
				'Reports',
				'Tmp'
			));

		} catch (\PhpOptions\UserBadCallException $e) {// @codeCoverageIgnoreStart
			$this->printInfo('ERROR: ' . $e->getMessage());
			die();
		}// @codeCoverageIgnoreEnd

		return $options;
	}


	// ---- PARTS OF RUNNING ----


	/**
	 * Generate tests from tests templates
	 *
	 * @return void
	 */
	private function generateTests()
	{
		$this->printHeader('Generate Tests');

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
			$templatesDirs = $this->getDirs($templatesDir);
		}

		foreach ($templatesDirs as $templateDir)
		{
			$generator->addTests($templateDir);
		}
		$testsNumber = $generator->generateAll();
		$this->printInfo($testsNumber . ' tests were generated from ' . count($templatesDirs) . ' temapltes into directory "' . $testsDir . '"');
	}


	/**
	 * Run all tests
	 *
	 * @return void
	 */
	private function runTests()
	{
		$this->printHeader('Run Tests');

		$options = $this->options;
		$testsDir = $options->get('Tests');
		$reportsDir = $options->get('Reports');
		$processors = $options->get('Processors');
		$processorsExxclude = $options->get('Processors exclude');
		$repeating = $options->get('Repeating');
		$tmpDir = $options->get('Tmp');

		// @HACK from PhpOptions 2.0.0 it is not necessary
		if (!$repeating)
		{
			$repeating = 1;
		}
		// \@HACK

		$runner = new \XSLTBenchmarking\TestsRunner\Runner(
			$this->factory,
			new \XSLTBenchmarking\TestsRunner\Params(),
			new \XSLTBenchmarking\TestsRunner\TestRunner(
				$this->factory,
				new \XSLTBenchmarking\TestsRunner\Processor(),
				$processors,
				$processorsExclude,
				$repeating,
				new \XSLTBenchmarking\TestsRunner\Controlor(),
				$tmpDir
			),
			new \XSLTBenchmarking\Reports\Printer($reportsDir),
			$testsDir
		);

		$testsDirs = $options->get('Tests dirs');

		// generate all templates
		if ($testsDirs === TRUE)
		{
			$testsDirs = $this->getDirs($testsDir);
		}

		foreach ($testsDirs as $testDir)
		{
			$generator->addTest($testDir);
		}

		$testsNumber = $runner->runAll();

		$this->printInfo($testsNumber . ' were run.');
	}


	// ---- HELPS FUNCTIONS ----


	/**
	 * Return subdirectories names
	 *
	 * @param string $path Directory that is scanned
	 *
	 * @return array
	 */
	private function getDirs($path)
	{
		$allResources = scandir($path);

		$dirs = array();
		foreach ($allResources as $resource)
		{
			if (!in_array($resource, array('.', '..')) && is_dir(P::m($path, $resource)))
			{
				$dirs[] = $resource;
			}
		}

		return $dirs;
	}


	/**
	 * Print header of one runnig script
	 *
	 * @param string $header Text of printed header
	 * @param bool $turnOff Turn of printing
	 *
	 * @return void
	 */
	private function printHeader($header, $turnOff = TRUE)
	{
		if ($turnOff)
		{
			$header = $header . ':';
			$line = str_repeat('-', strlen($header));
			$this->printInfo($header);
			$this->printInfo($line);
		}
	}


	/**
	 * Print information text
	 *
	 * @param string $info Text of printed info
	 * @param bool $turnOff Turn of printing
	 *
	 * @return void
	 */
	private function printInfo($info = '', $turnOff = TRUE)
	{
		if ($turnOff)
		{
			fwrite(STDOUT, $info . PHP_EOL);
		}
	}


}
