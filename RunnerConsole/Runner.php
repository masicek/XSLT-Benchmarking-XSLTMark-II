<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\RunnerConsole;

define ('LIBS', __DIR__ . '/../Libs');
define ('ROOT', __DIR__ . '/..');

require_once LIBS . '/PhpOptions/PhpOptions.min.php';
require_once ROOT . '/TestsGenerator/Generator.php';

use PhpOptions\Options;
use PhpOptions\Option;
use XSLTBenchmarking\TestsGenerator\Generator;

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

			$description = "Console XSLT Benchmarking\n";
			$description .= "author: Viktor Masicek <viktor@masicek.net>";
			$options->description($description);

			$optionsList = array();

			// directories
			$optionsList[] = Option::directory('Templates', $baseDir)
				->short()
				->value(FALSE)
				->defaults('./TestsTemplates')
				->description('Directory containing templates for generating tests');
			$optionsList[] = Option::directory('Tests', $baseDir)
				->short()
				->value(FALSE)
				->defaults('./Tests')
				->description('Directory for generating tests');
			$optionsList[] = Option::directory('Tmp', $baseDir)
				->short()
				->value(FALSE)
				->defaults('./Tmp')
				->description('Temporary directory');

			// generating tests
			$optionsList[] = Option::make('Generate')->description('Generating tests from templates');
			$optionsList[] = Option::series('Templates names')
				->short('n')
				->description('Names of tests templates for generating, separated by one of this characters ", ;|"');

			// run tests
			$optionsList[] = Option::make('Run')->description('Run prepared tests');

			// print reports
			$optionsList[] = Option::make('Print reports')
				->short('p')
				->long('print')
				->description('Print reports of tests');

			$options->add($optionsList);

			// dependences + groups
			$options->dependences('Generate', array('Templates', 'Templates names', 'Tests', 'Tmp'));
			$options->group('Generating tests', array('Generate', 'Templates', 'Templates names', 'Tests', 'Tmp'));
		} catch (\PhpOptions\UserBadCallException $e) {
			$this->printInfo('ERROR: ' . $e->getMessage());
			die();
		}

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
		{
			fwrite(STDOUT, $options->getHelp());
			return;
		}

		// generating tests
		if ($options->get('Generate'))
		{
			$this->generateTests();
		}

		// run tests
		if ($options->get('Run'))
		{
			// TODO
		}

		// print reports
		if ($options->get('Print reports'))
		{
			// TODO
		}
	}


	// ---- PARTS OF RUNNING ----


	private function generateTests()
	{
		$this->printHeader('Generate Tests');

		$options = $this->options;
		$templatesDir = $options->get('Templates');
		$testsDir = $options->get('Tests');
		$tmpDir = $options->get('Tmp');
		$generator = new Generator($templatesDir, $testsDir, $tmpDir);

		$templatesNames = $options->get('Templates names');

		foreach ($templatesNames as $templateName)
		{
			$generator->addTests($templateName);
		}
		$generator->generateAll();
		$this->printInfo('Tests were generated from ' . count($templatesNames) . ' temapltes into directory "' . $testsDir . '"');
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
			fwrite(STDOUT, "$info\n");
		}
	}


}
