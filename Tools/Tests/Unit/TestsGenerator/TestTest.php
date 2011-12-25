<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Test;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Test;

require_once ROOT_TOOLS . '/TestsGenerator/Test.php';

/**
 * TestTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestTest extends TestCase
{

	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::__construct
	 */
	public function testTest()
	{
		$test = new Test('Foo');
		$this->assertEquals('Foo', $this->getPropertyValue($test, 'name'));
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatePath
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getTemplatePath
	 */
	public function testTemplatePath()
	{
		$test = new Test('Foo');

		$test->setTemplatePath('path/template.tpl.xslt');
		$this->assertEquals($this->setDirSep('path/template.tpl.xslt'), $test->getTemplatePath());

		$test->setTemplatePath('path/template.tpl.xsl');
		$this->assertEquals($this->setDirSep('path/template.tpl.xsl'), $test->getTemplatePath());
	}


	/**
	 * @dataProvider providerTemplatePathWrong
	 *
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatePath
	 */
	public function testTemplatePathWrong($path)
	{
		$test = new Test('Foo');
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$test->setTemplatePath($path);
	}


	/**
	 * provider for testTemplatePathWrong1
	 */
	public function providerTemplatePathWrong()
	{
		return array(
			array('Lorem ipsum'),
			array('lorem.tpl'),
			array('lorem.xslt'),
			array('loremtpl.xslt'),
			array('lorem.tplxslt'),
			array('loremtplxslt'),
		);
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatingType
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getTemplatingType
	 */
	public function testTemplatingType()
	{
		$test = new Test('Foo');
		$test->setTemplatingType('Lorem ipsum');
		$this->assertEquals('Lorem ipsum', $test->getTemplatingType());
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setPath
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getPath
	 */
	public function testPath()
	{
		$test = new Test('Foo Bar');
		$test->setPath('root_path');
		$this->assertEquals($this->setDirSep('root_path/foo-bar'), $test->getPath());
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getXsltName
	 */
	public function testGetXsltName()
	{
		$test = new Test('Foo');
		$test->setTemplatePath('path/template.tpl.xslt');
		$this->assertEquals('template.xslt', $test->getXsltName());
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getXsltPath
	 */
	public function testGetXsltPath()
	{
		$test = new Test('Foo Bar');
		$test->setPath('root/path');

		$test->setTemplatePath('lorem/template.tpl.xslt');
		$this->assertEquals($this->setDirSep('root/path/foo-bar/template.xslt'), $test->getXsltPath());

		$test->setTemplatePath('lorem/myPrefix.template.tpl.xslt');
		$this->assertEquals($this->setDirSep('root/path/foo-bar/myPrefix.template.xslt'), $test->getXsltPath());
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::addSettings
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getSettings
	 */
	public function testSettings()
	{
		$test = new Test('Foo');
		$test->addSettings(array('foo1' => 'bar1'));
		$test->addSettings(array('foo2' => 'bar2', 'foo3' => 'bar3'));
		$this->assertEquals(
			array('foo1' => 'bar1', 'foo2' => 'bar2', 'foo3' => 'bar3'),
			$test->getSettings()
		);
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::addFilesPaths
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getFilesPaths
	 */
	public function testFilesPaths()
	{
		$test = new Test('Foo');
		$test->addFilesPaths(array('foo1/bar1' => 'foo1/car1'));
		$test->addFilesPaths(array('foo2/bar2' => 'foo2/car2', 'foo3/bar3' => 'foo3/car3'));
		$this->assertEquals(
			array(
				$this->setDirSep('foo1/bar1') => $this->setDirSep('foo1/car1'),
				$this->setDirSep('foo2/bar2') => $this->setDirSep('foo2/car2'),
				$this->setDirSep('foo3/bar3') => $this->setDirSep('foo3/car3'),
			),
			$test->getFilesPaths()
		);
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setParamsFilePath
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getParamsFilePath
	 */
	public function testParamsFilePath()
	{
		$test = new Test('Foo Bar');
		$test->setPath('root/path');

		$test->setParamsFilePath('myName.xml');
		$this->assertEquals($this->setDirSep('root/path/foo-bar/myName.xml'), $test->getParamsFilePath());
	}


}
