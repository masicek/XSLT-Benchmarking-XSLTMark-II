<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Processors;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Processor;

require_once ROOT_TOOLS . '/TestsRunner/Processors/Processor.php';

/**
 * ScriptsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class ScriptsTest extends TestCase
{

	private $processorsDir;

	public function setUp()
	{
		$this->processorsDir = $this->setDirSep(ROOT_TOOLS . '/TestsRunner/Processors/Scripts/');
	}


	/**
	 * Check expected processors and available processors
	 */
	public function testCountAndNames()
	{
		$processor = new Processor();
		$processors = $processor->getAvailable();

		switch (PHP_OS)
		{
			case 'WINNT':
				$expectedProcessors = array(
					'php4xslt' => 'php4xslt.php',
					'phpxsl' => 'phpxsl.php',
					'saxon' => 'saxon.bat',
				);
				break;

			case 'Linux':
				$expectedProcessors = array(
					'php4xslt' => 'php4xslt.php',
					'phpxsl' => 'phpxsl.php',
					'saxon' => 'saxon.sh',
				);
				break;
		}

		$this->assertEquals($expectedProcessors, $processors);
	}


	/**
	 * @dataProvider providerScripts
	 */
	public function test_runOk($prefix, $scriptFile)
	{
		$script = $prefix . $this->processorsDir . $scriptFile;

		$templatePath = $this->setDirSep(__DIR__ . '/template.xslt');;
		$inputXmlPath = $this->setDirSep(__DIR__ . '/input.xml');;
		$outputPath = $this->setDirSep(__DIR__ . '/output.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/expectedOutput.xml');

		$arguments = array($templatePath, $inputXmlPath, $outputPath);
		$command = $script . ' ' . implode(' ', $arguments) . ' ' . $this->setDirSep(LIBS_TOOLS . '/Processors');

		$this->assertFileNotExists($outputPath);

		exec($command, $output);
		$output = implode(PHP_EOL, $output);

		$this->assertEquals('OK', $output);
		$this->assertFileExists($outputPath);
		$this->assertXmlFileEqualsXmlFile($expectedOutputPath, $outputPath);
		unlink($outputPath);
	}


	/**
	 * @dataProvider providerScripts
	 */
	public function test_runError($prefix, $scriptFile)
	{
		$script = $prefix . $this->processorsDir . $scriptFile;

		$templatePath = $this->setDirSep(__DIR__ . '/templateWrong.xslt');;
		$inputXmlPath = $this->setDirSep(__DIR__ . '/input.xml');;
		$outputPath = $this->setDirSep(__DIR__ . '/output.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/expectedOutput.xml');

		$arguments = array($templatePath, $inputXmlPath, $outputPath);
		$command = $script . ' ' . implode(' ', $arguments) . ' ' . $this->setDirSep(LIBS_TOOLS . '/Processors');

		$this->assertFileNotExists($outputPath);

		exec($command, $output);
		$output = implode(PHP_EOL, $output);

		$this->assertNotEquals('OK', $output);
		$this->assertFileNotExists($outputPath);
	}


	public function providerScripts()
	{
		$libsProcessors = $this->setDirSep(LIBS_TOOLS . '/Processors');
		switch (PHP_OS)
		{
			case 'WINNT':
				$values = array(
//					'php4xslt' => array('php ', 'php4xslt.php'),
					'phpxsl' => array('php -d extension=' . $libsProcessors . '\libxslt\php_xsl.dll ', 'phpxsl.php'),
					'saxon' => array('', 'saxon.bat'),
				);
				break;

			case 'Linux':
				$values = array(
//					'php4xslt' => array('php ', 'php4xslt.php'),
					'phpxsl' => array('php -d extension=' . $libsProcessors . '\libxslt\xsl.so ', 'phpxsl.php'),
					'saxon' => array('', 'saxon.sh'),
				);
				break;
		}

		return $values;
	}


}
