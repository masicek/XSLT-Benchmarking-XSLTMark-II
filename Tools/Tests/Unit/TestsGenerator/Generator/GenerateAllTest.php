<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Generator;

require_once ROOT_TOOLS . '/TestsGenerator/Generator.php';
require_once ROOT_TOOLS . '/TestsGenerator/Test.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Generator;
use \XSLTBenchmarking\TestsGenerator\Test;

/**
 * AddAndGetTestsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\Generator::generateAll
 * @covers XSLTBenchmarking\TestsGenerator\Generator::generateTest
 * @covers XSLTBenchmarking\TestsGenerator\Generator::generateInputOutputCouples
 */
class GenerateAllTest extends TestCase
{


	public function test()
	{
		$fixture = $this->setDirSep(__DIR__ . '/FixtureGenerateAll');
		$tests = $this->setDirSep(__DIR__ . '/tests');
		mkdir($tests);

		$generator = new Generator(__DIR__, __DIR__, __DIR__);

		// make tests
		$testsList = array();

		// first test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name - First');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep($fixture . '/template.tpl.xslt'));
		$this->setPropertyValue($test, 'templatingType', 'smarty');
		$this->setPropertyValue($test, 'path', $this->setDirSep($tests . '/test-name-first'));
		$this->setPropertyValue($test, 'settings', array('testVariable1' => 'Lorem ipsum 1.1', 'testVariable2' => 'Lorem ipsum 1.2'));
		$this->setPropertyValue($test, 'filesPaths', array(
			$this->setDirSep($fixture . '/one.xml') => $this->setDirSep($fixture . '/genOne.xml'),
			$this->setDirSep($fixture . '/two.xml') => $this->setDirSep($fixture . '/genTwo.xml'),
		));
		$this->setPropertyValue($test, 'paramsFilePath', $this->setDirSep($tests . '/test-name-first/myParams.xml'));
		$testsList[] = $test;

		// second test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name - Second');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep($fixture . '/template.tpl.xslt'));
		$this->setPropertyValue($test, 'templatingType', 'smarty');
		$this->setPropertyValue($test, 'path', $this->setDirSep($tests . '/test-name-second'));
		$this->setPropertyValue($test, 'settings', array('testVariable1' => 'Lorem ipsum 2.1', 'testVariable2' => 'Lorem ipsum 2.2'));
		$this->setPropertyValue($test, 'filesPaths', array(
			$this->setDirSep($fixture . '/one.xml') => $this->setDirSep($fixture . '/genOne.xml'),
		));
		$this->setPropertyValue($test, 'paramsFilePath', $this->setDirSep($tests . '/test-name-second/__params.xml'));
		$testsList[] = $test;

		$this->setPropertyValue($generator, 'templates', $testsList);

		// fisrt test
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/template.xslt'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/one.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/genOne.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/two.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/genTwo.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-first/myParams.xml'));
		// second test
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-second/template.xslt'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-second/one.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-second/genOne.xml'));
		$this->assertFileNotExists($this->setDirSep($tests . '/test-name-second/__params.xml'));

		// generate all tests from template
		$generator->generateAll();

		// fisrt test
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/template.xslt'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/one.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/genOne.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/two.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/genTwo.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-first/myParams.xml'));
		// second test
		$this->assertFileExists($this->setDirSep($tests . '/test-name-second/template.xslt'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-second/one.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-second/genOne.xml'));
		$this->assertFileExists($this->setDirSep($tests . '/test-name-second/__params.xml'));

		// content
		$this->assertFileEquals(
			$this->setDirSep($fixture . '/expectedTemplate1.xslt'),
			$this->setDirSep($tests . '/test-name-first/template.xslt')
		);
		$this->assertFileEquals(
			$this->setDirSep($fixture . '/expectedTemplate2.xslt'),
			$this->setDirSep($tests . '/test-name-second/template.xslt')
		);
		$this->assertXmlFileEqualsXmlFile(
			$this->setDirSep($fixture . '/expected__params1.xml'),
			$this->setDirSep($tests . '/test-name-first/myParams.xml')
		);
		$this->assertXmlFileEqualsXmlFile(
			$this->setDirSep($fixture . '/expected__params2.xml'),
			$this->setDirSep($tests . '/test-name-second/__params.xml')
		);


		// remove all generated files and directories
		// fisrt test
		unlink($this->setDirSep($tests . '/test-name-first/template.xslt'));
		unlink($this->setDirSep($tests . '/test-name-first/one.xml'));
		unlink($this->setDirSep($tests . '/test-name-first/genOne.xml'));
		unlink($this->setDirSep($tests . '/test-name-first/two.xml'));
		unlink($this->setDirSep($tests . '/test-name-first/genTwo.xml'));
		unlink($this->setDirSep($tests . '/test-name-first/myParams.xml'));
		rmdir($this->setDirSep($tests . '/test-name-first'));
		// second test
		unlink($this->setDirSep($tests . '/test-name-second/template.xslt'));
		unlink($this->setDirSep($tests . '/test-name-second/one.xml'));
		unlink($this->setDirSep($tests . '/test-name-second/genOne.xml'));
		unlink($this->setDirSep($tests . '/test-name-second/__params.xml'));
		rmdir($this->setDirSep($tests . '/test-name-second'));

		rmdir($tests);

		// remove temporary file
		$files = scandir(__DIR__);
		foreach ($files as $file)
		{
			if (strpos($file, '.file.template.tpl.xslt.php') !== FALSE)
			{
				unlink($this->setDirSep(__DIR__ . '/' . $file));
			}
		}
	}

}
