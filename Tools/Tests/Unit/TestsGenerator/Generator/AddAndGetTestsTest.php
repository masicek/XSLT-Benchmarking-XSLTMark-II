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
 * @covers XSLTBenchmarking\TestsGenerator\Generator::addTests
 * @covers XSLTBenchmarking\TestsGenerator\Generator::getTests
 */
class AddAndGetTestsTest extends TestCase
{


	public function test()
	{
		$templates = $this->setDirSep(__DIR__ . '/A');
		$templateXYZ = $this->setDirSep(__DIR__ . '/A/XYZ');
		$tests = $this->setDirSep(__DIR__ . '/B');
		$tmp = $this->setDirSep(__DIR__ . '/C');

		mkdir($templates);
		mkdir($templateXYZ);
		mkdir($tests);
		mkdir($tmp);

		copy(
			$this->setDirSep(__DIR__ . '/AddAndGetTests.xml'),
			$this->setDirSep($templateXYZ . '/params.xml')
		);

		$generator = new Generator($templates, $tests, $tmp);
		$generator->addTests('XYZ', 'params.xml');
		$addedTests = $generator->getTests();

		// make expected tests
		$expectedTests = array();

		// first test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name - First');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep($templateXYZ . '/test.tpl.xslt'));
		$this->setPropertyValue($test, 'templatingType', 'smarty');
		$this->setPropertyValue($test, 'path', $this->setDirSep($tests . '/test-name-first'));
		$this->setPropertyValue($test, 'settings', array('first seting' => 'Lorem ipsum', 'second setting' => 123));
		$this->setPropertyValue($test, 'filesPaths', array(
			$this->setDirSep($templateXYZ . '/one.xml') => $this->setDirSep($tmp . '/genOne.xml'),
			$this->setDirSep($templateXYZ . '/two.xml') => $this->setDirSep($tmp . '/genTwo.xml'),
		));
		$expectedTests[] = $test;

		// second test
		$test = new Test('');
		$this->setPropertyValue($test, 'name', 'Test name - Second');
		$this->setPropertyValue($test, 'templatePath', $this->setDirSep($templateXYZ . '/test.tpl.xslt'));
		$this->setPropertyValue($test, 'templatingType', 'smarty');
		$this->setPropertyValue($test, 'path', $this->setDirSep($tests . '/test-name-second'));
		$this->setPropertyValue($test, 'settings', array('setting' => 999));
		$this->setPropertyValue($test, 'filesPaths', array(
			$this->setDirSep($templateXYZ . '/one.xml') => $this->setDirSep($tmp . '/genOne.xml'),
			$this->setDirSep($templateXYZ . '/two.xml') => $this->setDirSep($tmp . '/genTwo.xml'),
			$this->setDirSep($templateXYZ . '/three.xml') => $this->setDirSep($templateXYZ . '/one.xml'),
		));
		$expectedTests[] = $test;

		$this->assertEquals($expectedTests, $addedTests);

		unlink($this->setDirSep($templateXYZ . '/params.xml'));
		rmdir($templateXYZ);
		rmdir($templates);
		rmdir($tests);
		unlink($this->setDirSep($tmp . '/genOne.xml'));
		unlink($this->setDirSep($tmp . '/genTwo.xml'));
		rmdir($tmp);
	}

}
