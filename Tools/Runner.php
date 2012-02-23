<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Tools;

define('DATA_TOOLS', __DIR__ . '/Data');
define('TESTS_TOOLS', __DIR__ . '/Tests');
define('ROOT_TOOLS', __DIR__ . '/../XSLTBenchmarking');
define('LIBS_TOOLS', __DIR__ . '/../Libs');

if (!defined('LIBS'))
{
	define ('LIBS', LIBS_TOOLS);
}
if (!defined('ROOT'))
{
	define ('ROOT', ROOT_TOOLS);
}
if (!defined('VERSION'))
{
	define('VERSION', 'testing');
}


require_once LIBS . '/PhpOptions/PhpOptions.min.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Printer.php';

use PhpOptions\Option;
use PhpOptions\Options;
use PhpPath\P;

/**
 * Runner of scripts for run tests and generating documentation
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Runner
{

	/**
	 * Command-line options
	 *
	 * @var PhpOptions\Options
	 */
	private $options;

	/**
	 * Flag of including PHPUnit tests
	 *
	 * @var bool
	 */
	private static $testsIncludeDone = FALSE;


	// ---- RUNNING ----


	/**
	 * Define expected options
	 */
	public function __construct()
	{
		// set options + help
		$optionsList = array();
		$optionsList[] = Option::make('Help')->description('Show this help');
		$optionsList[] = Option::make('All')->description('Run script parts (Tests, Docs)');
		$optionsList[] = Option::make('Tests')->description('Run all tests');
		$optionsList[] = Option::make('Skipped on')->description('Turn on skipping of slow tests prepared for skipping');
		$optionsList[] = Option::make('Tests unit')->short()->long('tu')->description('Run unit tests');
		$optionsList[] = Option::make('Tests regression')->short()->long('tr')->description('Run regression tests');
		$optionsList[] = Option::make('Docs')->description('Run script for generating API documenation of XSLT Benchmarking');

		$options = new Options();
		$options->add($optionsList);
		$options->defaults('Help');
		$options->description(
			'XSTL Benchmarking' . PHP_EOL .
			'author: Viktor Masicek <viktor@masicek.net>' . PHP_EOL . PHP_EOL .
			'Script for running tests and generating API documentation of XSLT Benchmarking.'
		);

		$this->options = $options;

		\XSLTBenchmarking\Printer::$mode = \XSLTBenchmarking\Printer::MODE_TEST;
	}


	/**
	 * Run scripts by options defined in command-line
	 *
	 * @return void
	 */
	public function run()
	{
		$options = $this->options;

		// help
		if ($options->get('Help'))
		{
			fwrite(STDOUT, $options->getHelp());
			return;
		}

		$all = $options->get('All');

		// tests
		if ($all || $options->get('Tests'))
		{
			$this->runTestsAll();
		}
		else
		{
			if ($options->get('Tests unit'))
			{
				$this->runTestsUnit();
			}
			if ($options->get('Tests regression'))
			{
				$this->runTestsRegression();
			}
		}

		// documentation
		if ($all || $options->get('Docs'))
		{
			$this->generateDocumentation();
		}
	}


	// ---- PARTS OF RUNNING ----


	/**
	 * Generate dodumentation
	 *
	 * @return void
	 */
	private function generateDocumentation()
	{
		$this->printHeader('Generate Documentation');

		$sources = array();
		$sources[] = P::m(ROOT_TOOLS, 'RunnerConsole');
		$sources[] = P::m(ROOT_TOOLS, 'TestsGenerator');
		$sources[] = P::m(ROOT_TOOLS, 'DriversContainer.php');
		$sources[] = P::m(ROOT_TOOLS, 'Exceptions.php');
		$sources = '--source ' . implode(' --source ', $sources);

		$destination = P::m(DATA_TOOLS, '/Docs');

		$options = array(
			'--title "XSTL Benchmarking"',
			$sources,
			'--todo yes',
			'--destination ' . $destination,
		);
		$options = implode(' ', $options);

		$apigen = P::m(LIBS, '/Apigen/apigen.php');
		passthru('php ' . $apigen . ' ' . $options);
	}


	/**
	 * Run tests
	 *
	 * @return void
	 */
	private function runTestsAll()
	{
		$this->printHeader('Run tests');

		$this->testsInclude();

		// run tests
		$coverage = P::m(DATA_TOOLS, '/Coverage');
		$tests = P::m(TESTS_TOOLS);
		$this->setArguments('boot.php',
			'--coverage-html ' . $coverage . '
			' . $tests
		);
		\PHPUnit_TextUI_Command::main(FALSE);

		$this->printInfo();
	}


	/**
	 * Run unit tests
	 *
	 * @return void
	 */
	private function runTestsUnit()
	{
		$this->printHeader('Run unit tests');

		$this->testsInclude();

		// run tests
		$coverage = P::m(DATA_TOOLS, '/Coverage');
		$tests = P::m(TESTS_TOOLS, '/Unit');
		$this->setArguments('boot.php',
			'--coverage-html ' . $coverage . '
			' . $tests
		);
		\PHPUnit_TextUI_Command::main(FALSE);

		$this->printInfo();
	}


	/**
	 * Run regression tests
	 *
	 * @return void
	 */
	private function runTestsRegression()
	{
		$this->printHeader('Run regression tests');

		$this->testsInclude();

		// run tests
		$coverage = P::m(DATA_TOOLS, '/Coverage');
		$tests = P::m(TESTS_TOOLS, '/Regression');
		$this->setArguments('boot.php',
			'--coverage-html ' . $coverage . '
			' . $tests
		);
		\PHPUnit_TextUI_Command::main(FALSE);

		$this->printInfo();
	}


	// ---- HELPS FUNCTIONS ----


	/**
	 * Include PHPUnit for runnig tests.
	 * Including is done one once.
	 *
	 * @return void
	 */
	private function testsInclude()
	{
		if (!self::$testsIncludeDone)
		{
			define('TEST_SKIPPED', (bool)$this->options->get('Skipped on'));

			self::$testsIncludeDone = TRUE;

			$this->mockeryInclude();

			// set libs as include path
			$libs = P::m(LIBS, '/PHPUnit');
			set_include_path(get_include_path() . PATH_SEPARATOR . $libs);
			require_once P::m('PHPUnit/Autoload.php');

			// include my TestCase
			require_once P::m(TESTS_TOOLS, '/TestCase.php');
		}
	}


	/**
	 * Include Mockery for tests
	 *
	 * @return void
	 */
	private function mockeryInclude()
	{
		$libs = P::m(LIBS, '/Mockery');
		set_include_path(get_include_path() . PATH_SEPARATOR . $libs);
		require_once P::m('Mockery/Loader.php');
		$loader = new \Mockery\Loader;
		$loader->register();
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
			fwrite(STDOUT, "$info\n");
		}
	}


	/**
	 * Simulate input command-line arguments
	 *
	 * @param string $arguments List of arguments
	 *
	 * @return void
	 */
	private function setArguments($scriptName, $arguments)
	{
		$arguments = preg_replace('/(' . "\r\n|\t" . ')+/', ' ', $arguments);
		$arguments = trim($arguments);
		$argumentsNew = '';
		$inQuation = FALSE;
		for ($i = 0; $i < strlen($arguments); $i++)
		{
			$char  = $arguments[$i];
			if ($char == '"' && !$inQuation)
			{
				$inQuation = TRUE;
			}
			elseif ($char == '"' && $inQuation)
			{
				$inQuation = FALSE;
			}

			if ($char == ' ' && $inQuation)
			{
				$argumentsNew .= '###SPACE###';
			}
			elseif ($char != '"')
			{
				$argumentsNew .= $char;
			}
		}
		$arguments = $argumentsNew;
		$arguments = preg_replace('/ +/', ' ', $arguments);

		$argv = explode(' ', trim($scriptName . ' ' . $arguments));

		for ($i = 0; $i < count($argv); $i++)
		{
			$argv[$i] = str_replace('###SPACE###', ' ', $argv[$i]);
		}

		$_SERVER['argv'] = $argv;
	}


}
