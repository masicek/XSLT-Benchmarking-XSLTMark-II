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


	public function testOk()
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
			$this->setDirSep(__DIR__ . '/FixtureAddAndGetTests/params.xml'),
			$this->setDirSep($templateXYZ . '/params.xml')
		);
		file_put_contents($this->setDirSep($templateXYZ . '/test.tpl.xslt'), '');
		file_put_contents($this->setDirSep($templateXYZ . '/one.xml'), '');
		file_put_contents($this->setDirSep($templateXYZ . '/two.xml'), '');
		file_put_contents($this->setDirSep($templateXYZ . '/three.xml'), '');

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
		$this->setPropertyValue($test, 'paramsFilePath', $this->setDirSep($tests . '/test-name-first/testName.xml'));
		$expectedTests['Test name - First'] = $test;

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
		$this->setPropertyValue($test, 'paramsFilePath', $this->setDirSep($tests . '/test-name-second/__params.xml'));
		$expectedTests['Test name - Second'] = $test;

		$this->assertEquals($expectedTests, $addedTests);

		unlink($this->setDirSep($templateXYZ . '/params.xml'));
		unlink($this->setDirSep($templateXYZ . '/test.tpl.xslt'));
		unlink($this->setDirSep($templateXYZ . '/one.xml'));
		unlink($this->setDirSep($templateXYZ . '/two.xml'));
		unlink($this->setDirSep($templateXYZ . '/three.xml'));
		rmdir($templateXYZ);
		rmdir($templates);
		rmdir($tests);
		unlink($this->setDirSep($tmp . '/genOne.xml'));
		unlink($this->setDirSep($tmp . '/genTwo.xml'));
		rmdir($tmp);
	}


	public function testNameCollision()
	{
		$generator = new Generator(__DIR__, __DIR__, __DIR__);
		file_put_contents($this->setDirSep(__DIR__ . '/FixtureDuplicateName/test.tpl.xslt'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/FixtureDuplicateName/lorem.xml'), '');
		$generator->addTests('FixtureDuplicateName');
		unlink($this->setDirSep(__DIR__ . '/FixtureDuplicateName/test.tpl.xslt'));
		unlink($this->setDirSep(__DIR__ . '/FixtureDuplicateName/lorem.xml'));
		$this->setExpectedException('\XSLTBenchmarking\CollisionException', 'Duplicate name of test "Duplicate name - Test name"');
		$generator->addTests('FixtureDuplicateName');
		$addedTests = $generator->getTests();
	}


}
