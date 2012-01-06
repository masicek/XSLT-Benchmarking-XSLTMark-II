<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator;

require_once ROOT_TOOLS . '/RunnerConsole/Runner.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * TestsGeneratorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\RunnerConsole\Runner::__construct
 * @covers XSLTBenchmarking\RunnerConsole\Runner::defineOptions
 * @covers XSLTBenchmarking\RunnerConsole\Runner::run
 * @covers XSLTBenchmarking\RunnerConsole\Runner::generateTests
 * @covers XSLTBenchmarking\RunnerConsole\Runner::getDirs
 * @covers XSLTBenchmarking\RunnerConsole\Runner::printHeader
 * @covers XSLTBenchmarking\RunnerConsole\Runner::printInfo
 */
class TestsGeneratorTest extends TestCase
{


	public function test()
	{
		// used directories
		$baseDir = __DIR__;
		$templates = $this->setDirSep($baseDir . '/Templates');
		$tests = $this->setDirSep($baseDir . '/TestsGenerated');
		$testsExpected = $this->setDirSep($baseDir . '/TestsExpected');
		$tmp = $this->setDirSep($baseDir . '/Tmp');

		// simulate arguments for generating tests
		$this->setArguments(
			'-g ' .
			'--templates "./Templates" ' .
			'--tests "./TestsGenerated" ' .
			'--tmp "./Tmp" '
		);

		// check not existence of tests
		$this->assertFalse(is_dir($this->setDirSep($tests . '/modify-element-rename')));
		$this->assertFalse(is_dir($this->setDirSep($tests . '/modify-element-remove')));
		$this->assertFalse(is_dir($this->setDirSep($tests . '/modify-element-copy')));
		$this->assertFalse(is_dir($this->setDirSep($tests . '/value-to-attribute-first')));

		$runner = new \XSLTBenchmarking\RunnerConsole\Runner($baseDir);
		$runner->run();

		// check generated tests
		$this->assertTrue(is_dir($this->setDirSep($tests . '/modify-element-rename')));
		$this->assertTrue(is_dir($this->setDirSep($tests . '/modify-element-remove')));
		$this->assertTrue(is_dir($this->setDirSep($tests . '/modify-element-copy')));
		$this->assertTrue(is_dir($this->setDirSep($tests . '/value-to-attribute-first')));

		// test MODIFY ELEMENT - REMOVE
		$generatedBase = $this->setDirSep($tests . '/modify-element-remove');
		$expectedBase = $this->setDirSep($testsExpected . '/modify-element-remove');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, '__params.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'test.xslt');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'zeroElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'oneElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'manyElements.xml');
		// test MODIFY ELEMENT - RENAME
		$generatedBase = $this->setDirSep($tests . '/modify-element-rename');
		$expectedBase = $this->setDirSep($testsExpected . '/modify-element-rename');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'myParams.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'test.xslt');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'oneElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'oneNewElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoNewElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'manyElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'manyNewElements.xml');
		// test MODIFY ELEMENT - COPY
		$generatedBase = $this->setDirSep($tests . '/modify-element-copy');
		$expectedBase = $this->setDirSep($testsExpected . '/modify-element-copy');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, '__params.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'oneElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'manyElements.xml');
		// test VALUE TO ATTRIBUTE - FIRST
		$generatedBase = $this->setDirSep($tests . '/value-to-attribute-first');
		$expectedBase = $this->setDirSep($testsExpected . '/value-to-attribute-first');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, '__params.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'zeroElement.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoElements.xml');
		$this->assertFileExistAndEquals($generatedBase, $expectedBase, 'twoNewElements.xml');

		// remove generated tests
		$this->removeGeneratedTests($tests);
		rmdir($tests);

		// remove tmp directory
		$files = scandir($tmp);
		foreach ($files as $file)
		{
			if (strpos($file, '.file.test.tpl.xslt.php') !== FALSE)
			{
				unlink($this->setDirSep($tmp . '/' . $file));
			}
		}
		unlink($this->setDirSep($tmp . '/manyElements.xml'));
		unlink($this->setDirSep($tmp . '/manyNewElements.xml'));
		rmdir($tmp);
	}


	private function assertFileExistAndEquals($generatedBase, $expectedBase, $name)
	{
		$this->assertFileExists($this->setDirSep($generatedBase . '/' . $name));
		$this->assertXmlFileEqualsXmlFile(
			$this->setDirSep($generatedBase . '/' . $name),
			$this->setDirSep($expectedBase . '/' . $name)
		);
	}


	private function removeGeneratedTests($tests)
	{
		// MODIFY ELEMENT - REMOVE
		$base = $this->setDirSep($tests . '/modify-element-remove/');
		unlink($this->setDirSep($base . '__params.xml'));
		unlink($this->setDirSep($base . 'test.xslt'));
		unlink($this->setDirSep($base . 'zeroElement.xml'));
		unlink($this->setDirSep($base . 'oneElement.xml'));
		unlink($this->setDirSep($base . 'twoElements.xml'));
		unlink($this->setDirSep($base . 'manyElements.xml'));
		rmdir($base);
		// MODIFY ELEMENT - RENAME
		$base = $this->setDirSep($tests . '/modify-element-rename/');
		unlink($this->setDirSep($base . 'myParams.xml'));
		unlink($this->setDirSep($base . 'test.xslt'));
		unlink($this->setDirSep($base . 'oneElement.xml'));
		unlink($this->setDirSep($base . 'oneNewElement.xml'));
		unlink($this->setDirSep($base . 'twoElements.xml'));
		unlink($this->setDirSep($base . 'twoNewElements.xml'));
		unlink($this->setDirSep($base . 'manyElements.xml'));
		unlink($this->setDirSep($base . 'manyNewElements.xml'));
		rmdir($base);
		// MODIFY ELEMENT - COPY
		$base = $this->setDirSep($tests . '/modify-element-copy/');
		unlink($this->setDirSep($base . '__params.xml'));
		unlink($this->setDirSep($base . 'test.xslt'));
		unlink($this->setDirSep($base . 'oneElement.xml'));
		unlink($this->setDirSep($base . 'twoElements.xml'));
		unlink($this->setDirSep($base . 'manyElements.xml'));
		rmdir($base);
		// VALUE TO ATTRIBUTE - FIRST
		$base = $this->setDirSep($tests . '/value-to-attribute-first/');
		unlink($this->setDirSep($base . '__params.xml'));
		unlink($this->setDirSep($base . 'test.xslt'));
		unlink($this->setDirSep($base . 'zeroElement.xml'));
		unlink($this->setDirSep($base . 'twoElements.xml'));
		unlink($this->setDirSep($base . 'twoNewElements.xml'));
		rmdir($base);
	}


}
