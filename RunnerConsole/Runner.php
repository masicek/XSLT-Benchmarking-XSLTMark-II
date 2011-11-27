<?php

/**
 * XSLT Benchmarking
 * @link git@github.com:masicek/XSLT-Benchmarking.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\RunnerConsole;

require_once __DIR__ . '/../Libs/PhpOptions/Options.php';
require_once __DIR__ . '/../Libs/PhpOptions/Option.php';
require_once __DIR__ . '/../Libs/PhpDirectory/Directory.php';
require_once __DIR__ . '/../TestsGenerator/Generator.php';

use PhpOptions\Options;
use PhpOptions\Option;
use PhpDirectory\Directory;
use XSLTBenchmark\TestsGenerator\Generator;

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
	 * Define expected options
	 */
	public function __construct($baseDir)
	{
		try {
			$options = new Options();

			// base settings of options
			$help = Option::make('Help')->description('Show this help');
			$options->add($help)->def('Help');

			$description = "Console XSLT Benchmarking\n";
			$description .= "author: Viktor Masicek <viktor@masicek.net>";
			$options->description($description);

			$optionsList = array();

			// directories
			$optionsList[] = Option::directory('Templates', $baseDir)
				->short()
				->value(FALSE)
				->def('TestsTemplates')
				->description('Directory containing templates for generating tests');
			$optionsList[] = Option::directory('Tests', $baseDir)
				->short()
				->value(FALSE)
				->def('Tests')
				->description('Directory for generating tests');
			$optionsList[] = Option::directory('Tmp', $baseDir)
				->short()
				->value(FALSE)
				->def('Tmp')
				->description('Temporary directory');

			// generating tests
			$optionsList[] = Option::make('Generate')->description('Generating tests from templates');
			$optionsList[] = Option::series('Templates names')
				->short()
				->description('Names of tests templates for generating, separated by one of this characters ", ;|"');

			// run tests
			$optionsList[] = Option::make('Run')->description('Run prepared tests');

			// print reports
			$optionsList[] = Option::make('Print reports')->short('p')->long('print')->description('Print reports of tests');

			$options->add($optionsList);

			// dependences + groups
			$options->dependences('Generate', array('Templates', 'Templates names', 'Tests', 'Tmp'));
			$options->group('Generating tests', array('Generate', 'Templates', 'Templates names', 'Tests', 'Tmp'));
		} catch (\PhpOptions\UserBadCallException $e) {
			echo $e->getMessage();
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
			echo $options->getHelp();
			return;
		}

		// generating tests
		if ($options->get('Generate'))
		{
			$templatesDir = $options->get('Templates');
			$testsDir = $options->get('Tests');
			$tmpDir = $options->get('Tmp');
			$generator = new Generator($templatesDir, $testsDir, $tmpDir);

			$templatesNames = $options->get('Templates names');
			if ($templatesNames)
			{
				foreach ($templatesNames as $templateName)
				{
					$generator->addTests($templateName);
				}
				$generator->generateAll();
				echo 'Tests were generated from ' . count($templatesNames) . ' temapltes into directory "' . $testsDir . '"' . "\n";
			}
		}

		// run tests
		if ($this->options->get('Run'))
		{
			// TODO
		}

		// print reports
		if ($this->options->get('Print reports'))
		{
			// TODO
		}
	}


}
