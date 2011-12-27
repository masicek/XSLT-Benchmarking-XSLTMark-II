<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\RunnerConsole;

if (!defined('LIBS'))
{
	define ('LIBS', __DIR__ . '/../../Libs');
}
if (!defined('ROOT'))
{
	define ('ROOT', __DIR__ . '/..');
}

require_once LIBS . '/PhpOptions/PhpOptions.min.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/TestsGenerator/Generator.php';
require_once ROOT . '/TestsRunner/Runner.php';


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


	// ---- RUNNING ----


	/**
	 * Define expected options
	 */
	public function __construct($baseDir)
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

			// directories
			$templates = $optionsList[] = Option::directory('Templates', $baseDir)
				->short()
				->value(FALSE)
				->defaults('../Data/TestsTemplates')
				->description('Directory containing templates for generating tests');
			$tests = $optionsList[] = Option::directory('Tests', $baseDir)
				->short()
				->value(FALSE)
				->defaults('../Data/Tests')
				->description('Directory for generating tests');
			$optionsList[] = Option::directory('Tmp', $baseDir)
				->short()
				->value(FALSE)
				->defaults('../Tmp')
				->description('Temporary directory');

			// generating tests
			$generate = $optionsList[] = Option::make('Generate')->description('Generating tests from templates');
			$optionsList[] = Option::series('Templates dirs', ',')
				->short()
				->value(FALSE)
				->defaults(TRUE)
				// HACK PHP_EOL will not be used in PhpOptions 2.0.0
				->description(
					'Subdirectories of director set by "' . $templates->getOptions() . '"' . PHP_EOL .
					'containing tests templates for generating, separated by character ",".' . PHP_EOL .
					'If this option is not set (or is set without value),' . PHP_EOL .
					'then all tests templates are selected' . PHP_EOL .
					'(all subdirectories are considered as tests templates).' . PHP_EOL .
					'This option make sense only for option "' . $generate->getOptions() . '".'
				);

			// run tests
			$run = $optionsList[] = Option::make('Run')->description('Run prepared tests');
			$optionsList[] = Option::series('Tests names', ',')
				->short()
				->value(FALSE)
				->defaults(TRUE)
				->description(
					'Subdirectories of director set by "' . $tests->getOptions() . '"' . PHP_EOL .
					'containing tests for runnig, separated by character ",".' . PHP_EOL .
					'If this option is not set (or is set without value),' . PHP_EOL .
					'then all tests are selected' . PHP_EOL .
					'(all subdirectories are considered as tests).' . PHP_EOL .
					'This option make sense only for option "' . $run->getOptions() . '".'
				);

			// print reports
			$optionsList[] = Option::make('Print reports')
				->short('p')
				->long('print')
				->description('Print reports of tests');

			$options->add($optionsList);

			// dependences + groups
			$options->dependences('Generate', array('Templates', 'Templates dirs', 'Tests', 'Tmp'));
			$options->group('Generating tests', array('Generate', 'Templates', 'Templates dirs', 'Tests', 'Tmp'));
			// HACK make group after solved bug in PhpOptions (issue #46)
			//$options->group('Runnig tests', array('Run', 'Tests', 'Tests dirs'));
		} catch (\PhpOptions\UserBadCallException $e) {// @codeCoverageIgnoreStart
			$this->printInfo('ERROR: ' . $e->getMessage());
			die();
		}// @codeCoverageIgnoreEnd

		$this->options = $options;
	}


	/**
	 * Run XSLT Benchmarking
	 * - show help
	 * @todo - generate tests
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

		// print reports
		if ($options->get('Print reports'))
		{
			// TODO
		}
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
		$generator = new \XSLTBenchmarking\TestsGenerator\Generator($templatesDir, $testsDir, $tmpDir);

		$templatesDirs = $options->get('Templates dirs');

		// HACK it will be solved with PhpOptions 2.0.0
		if ($templatesDirs == array('1'))
		{
			$templatesDirs = TRUE;
		}
		// /HACK

		// generate all templates
		if ($templatesDirs === TRUE)
		{
			$allResources = scandir($templatesDir);

			$templatesDirs = array();
			foreach ($allResources as $resource)
			{
				if (!in_array($resource, array('.', '..')) && is_dir(P::m($templatesDir, $resource)))
				{
					$templatesDirs[] = $resource;
				}
			}
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
		$runner = new \XSLTBenchmarking\TestsRunner\Runner($testsDir);

		$testsDirs = $options->get('Tests dirs');

		// HACK it will be solved with PhpOptions 2.0.0
		if ($testsDirs == array('1'))
		{
			$testsDirs = TRUE;
		}
		// /HACK

		// generate all templates
		if ($testsDirs === TRUE)
		{
			$allResources = scandir($testsDir);

			$testsDirs = array();
			foreach ($allResources as $resource)
			{
				if (!in_array($resource, array('.', '..')) && is_dir(P::m($testsDir, $resource)))
				{
					$testsDirs[] = $resource;
				}
			}
		}

		foreach ($testsDirs as $testName)
		{
			$generator->addTest($testName);
		}

		$runner->runAll();

		$this->printInfo('TODO - make this info');
	}


	// ---- HELPS FUNCTIONS ----


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
