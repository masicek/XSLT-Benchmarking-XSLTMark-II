<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\TestsGenerator\Test;

use \Tests\XSLTBenchmark\TestCase;
use \XSLTBenchmark\TestsGenerator\Test;

require_once ROOT_TOOLS . '/TestsGenerator/Test.php';

/**
 * TestTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestTest extends TestCase
{

	/**
	 * @covers XSLTBenchmark\TestsGenerator\Test::__construct
	 */
	public function testTest()
	{
		$test = new Test('Foo');
		$this->assertEquals('Foo', $this->getPropertyValue($test, 'name'));
	}


	/**
	 * @covers XSLTBenchmark\TestsGenerator\Test::setTemplatePath
	 * @covers XSLTBenchmark\TestsGenerator\Test::getTemplatePath
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
	 * @covers XSLTBenchmark\TestsGenerator\Test::setTemplatePath
	 */
	public function testTemplatePathWrong($path)
	{
		$test = new Test('Foo');
		$this->setExpectedException('\XSLTBenchmark\InvalidArgumentException');
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
	 * @covers XSLTBenchmark\TestsGenerator\Test::setTemplatingType
	 * @covers XSLTBenchmark\TestsGenerator\Test::getTemplatingType
	 */
	public function testTemplatingType()
	{
		$test = new Test('Foo');
		$test->setTemplatingType('Lorem ipsum');
		$this->assertEquals('Lorem ipsum', $test->getTemplatingType());
	}


	/**
	 * @covers XSLTBenchmark\TestsGenerator\Test::setPath
	 * @covers XSLTBenchmark\TestsGenerator\Test::getPath
	 */
	public function testPath()
	{
		$test = new Test('Foo Bar');
		$test->setPath('root_path');
		$this->assertEquals($this->setDirSep('root_path/foo-bar'), $test->getPath());
	}


	/**
	 * @covers XSLTBenchmark\TestsGenerator\Test::getXsltName
	 */
	public function testGetXsltName()
	{
		$test = new Test('Foo');
		$test->setTemplatePath('path/template.tpl.xslt');
		$this->assertEquals('template.xslt', $test->getXsltName());
	}


	/**
	 * @covers XSLTBenchmark\TestsGenerator\Test::getXsltPath
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
	 * @covers XSLTBenchmark\TestsGenerator\Test::addSettings
	 * @covers XSLTBenchmark\TestsGenerator\Test::getSettings
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
	 * @covers XSLTBenchmark\TestsGenerator\Test::addFilesPaths
	 * @covers XSLTBenchmark\TestsGenerator\Test::getFilesPaths
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


}
