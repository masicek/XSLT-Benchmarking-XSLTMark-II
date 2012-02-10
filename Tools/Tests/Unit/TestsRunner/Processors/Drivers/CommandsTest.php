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
 * CommandsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class CommandsTest extends TestCase
{


	/**
	 * Check expected processors and available processors
	 */
	public function testCountAndNames()
	{
		$processor = new Processor(__DIR__);
		$processors = $processor->getAvailable();

		switch (PHP_OS)
		{
			case 'WINNT':
				$this->assertEquals(4, count($processors));

				$this->assertArrayHasKey('libxslt1123cmd', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Libxslt1123cmdProcessorDriver', $processors['libxslt1123cmd']);
				$this->assertArrayHasKey('libxslt1123php', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Libxslt1123phpProcessorDriver', $processors['libxslt1123php']);
				$this->assertArrayHasKey('sablotron103cmd', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Sablotron103cmdProcessorDriver', $processors['sablotron103cmd']);
				$this->assertArrayHasKey('saxon655', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Saxon655ProcessorDriver', $processors['saxon655']);
				break;

			case 'Linux':
				$this->assertEquals(1, count($processors));
				$this->assertArrayHasKey('saxon655', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Saxon655ProcessorDriver', $processors['saxon655']);
				break;
		}
	}


	public function test_runOk()
	{
		$templatePath = $this->setDirSep(__DIR__ . '/Fixtures/template.xslt');
		$inputXmlPath = $this->setDirSep(__DIR__ . '/Fixtures/input.xml');
		$outputPath = $this->setDirSep(__DIR__ . '/output.xml');
		$expectedOutputPath = $this->setDirSep(__DIR__ . '/Fixtures/expectedOutput.xml');
		$errorPath = $this->setDirSep(__DIR__ . '/err.tmp');

		$processor = new Processor(__DIR__);
		$processorsDrivers = $processor->getAvailable();
		foreach ($processorsDrivers as $processorDriver)
		{
			$message = 'Error during transformation by "' . $processorDriver->getFullName() . '"';

			// make command from template
			$processor = new Processor(__DIR__);
			$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Processor', 'getCommand');
			$method->setAccessible(TRUE);
			$command = $method->invokeArgs($processor, array(
				$processorDriver->getCommandTemplate(),
				$templatePath,
				$inputXmlPath,
				$outputPath,
				$errorPath,
			));

			$this->assertFileNotExists($errorPath, $message);
			$this->assertFileNotExists($outputPath, $message);

			exec($command, $output);

			$output = implode(PHP_EOL, $output);
			$this->assertEquals('', $output, $message);

			if (is_file($errorPath))
			{
				$this->assertStringEqualsFile($errorPath, '', $message);
				unlink($errorPath);
			}

			$this->assertFileExists($outputPath, $message);
			$this->assertXmlFileEqualsXmlFile($expectedOutputPath, $outputPath, $message);
			unlink($outputPath);
		}
	}


	public function test_runError()
	{
		$templatePath = $this->setDirSep(__DIR__ . '/Fixtures/templateWrong.xslt');
		$inputXmlPath = $this->setDirSep(__DIR__ . '/Fixtures/input.xml');
		$outputPath = $this->setDirSep(__DIR__ . '/output.xml');
		$errorPath = $this->setDirSep(__DIR__ . '/err.tmp');

		$processor = new Processor(__DIR__);
		$processorsDrivers = $processor->getAvailable();
		foreach ($processorsDrivers as $processorDriver)
		{
			$message = 'Error during transformation by "' . $processorDriver->getFullName() . '"';

			// make command from template
			$processor = new Processor(__DIR__);
			$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Processor', 'getCommand');
			$method->setAccessible(TRUE);
			$command = $method->invokeArgs($processor, array(
				$processorDriver->getCommandTemplate(),
				$templatePath,
				$inputXmlPath,
				$outputPath,
				$errorPath,
			));

			$this->assertFileNotExists($errorPath, $message);
			$this->assertFileNotExists($outputPath, $message);

			exec($command, $output);

			$output = implode(PHP_EOL, $output);
			$this->assertEquals('', $output, $message);

			$this->assertStringNotEqualsFile($errorPath, '', $message);
			unlink($errorPath);

			$this->assertFileNotExists($outputPath, $message);
		}
	}


}
