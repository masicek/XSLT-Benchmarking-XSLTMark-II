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
require_once ROOT_TOOLS . '/TestsRunner/Processors/MemoryUsage/MemoryUsage.php';

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
		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$processors = $processor->getAvailable();

		switch (PHP_OS)
		{
			case 'WINNT':
				$this->assertEquals(10, count($processors));

				$this->assertArrayHasKey('libxslt1123php', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Libxslt1123phpProcessorDriver', $processors['libxslt1123php']);
				$this->assertArrayNotHasKey('libxslt1126php', $processors);

				$this->assertArrayHasKey('msxml60', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\MSXML60ProcessorDriver', $processors['msxml60']);
				$this->assertArrayHasKey('msxml30', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\MSXML30ProcessorDriver', $processors['msxml30']);

				$this->assertArrayHasKey('sablotron103cmd', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Sablotron103cmdProcessorDriver', $processors['sablotron103cmd']);

				$this->assertArrayHasKey('saxon655', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Saxon655ProcessorDriver', $processors['saxon655']);
				$this->assertArrayHasKey('saxonhe9402', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\SaxonHE9402ProcessorDriver', $processors['saxonhe9402']);

				$this->assertArrayHasKey('xt20051206', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\XT20051206ProcessorDriver', $processors['xt20051206']);

				$this->assertArrayHasKey('xalan271', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Xalan271ProcessorDriver', $processors['xalan271']);

				$this->assertArrayHasKey('xsltproc1123', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Xsltproc1123ProcessorDriver', $processors['xsltproc1123']);
				$this->assertArrayHasKey('xsltproc1126', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Xsltproc1126ProcessorDriver', $processors['xsltproc1126']);
				break;

			case 'Linux':
				$this->assertEquals(7, count($processors));

				$this->assertArrayNotHasKey('libxslt1123php', $processors);
				$this->assertArrayHasKey('libxslt1126php', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Libxslt1126phpProcessorDriver', $processors['libxslt1126php']);

				$this->assertArrayNotHasKey('msxml60', $processors);
				$this->assertArrayNotHasKey('msxml30', $processors);

				$this->assertArrayHasKey('sablotron103cmd', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Sablotron103cmdProcessorDriver', $processors['sablotron103cmd']);

				$this->assertArrayHasKey('saxon655', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Saxon655ProcessorDriver', $processors['saxon655']);
				$this->assertArrayHasKey('saxonhe9402', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\SaxonHE9402ProcessorDriver', $processors['saxonhe9402']);

				$this->assertArrayHasKey('xt20051206', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\XT20051206ProcessorDriver', $processors['xt20051206']);

				$this->assertArrayHasKey('xalan271', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Xalan271ProcessorDriver', $processors['xalan271']);

				$this->assertArrayNotHasKey('xsltproc1123', $processors);
				$this->assertArrayHasKey('xsltproc1126', $processors);
				$this->assertInstanceOf('\XSLTBenchmarking\TestsRunner\Xsltproc1126ProcessorDriver', $processors['xsltproc1126']);
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

		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$processorsDrivers = $processor->getAvailable();
		foreach ($processorsDrivers as $processorDriver)
		{
			$message = 'Error during transformation by "' . $processorDriver->getFullName() . '"';

			// make command from template
			$processor = new Processor(__DIR__, $memoryUsage);
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

		$memoryUsage = new \XSLTBenchmarking\TestsRunner\MemoryUsage(__DIR__);
		$processor = new Processor(__DIR__, $memoryUsage);
		$processorsDrivers = $processor->getAvailable();
		foreach ($processorsDrivers as $processorDriver)
		{
			$message = 'Error during transformation by "' . $processorDriver->getFullName() . '"';

			// make command from template
			$processor = new Processor(__DIR__, $memoryUsage);
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

			if (is_file($outputPath))
			{
				unlink($outputPath);
			}
		}
	}


}
