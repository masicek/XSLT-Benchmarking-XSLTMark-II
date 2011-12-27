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
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getName
	 */
	public function testTest()
	{
		$test = new Test('Foo');
		$this->assertEquals('Foo', $test->getName());
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatePath
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getTemplatePath
	 */
	public function testTemplatePath()
	{
		file_put_contents($this->setDirSep(__DIR__ . '/template.tpl.xsl'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/template.tpl.xslt'), '');

		$test = new Test('Foo');

		$test->setTemplatePath(__DIR__ . '/template.tpl.xsl');
		$this->assertEquals($this->setDirSep(__DIR__ . '/template.tpl.xsl'), $test->getTemplatePath());

		$test->setTemplatePath(__DIR__ . '/template.tpl.xslt');
		$this->assertEquals($this->setDirSep(__DIR__ . '/template.tpl.xslt'), $test->getTemplatePath());

		unlink($this->setDirSep(__DIR__ . '/template.tpl.xsl'));
		unlink($this->setDirSep(__DIR__ . '/template.tpl.xslt'));
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatePath
	 */
	public function testTemplatePathNotExistTemplate()
	{
		$test = new Test('Foo');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$test->setTemplatePath($this->setDirSep(__DIR__ . '/unknown.tpl.xslt'));
	}


	/**
	 * @dataProvider providerTemplatePathWrong
	 *
	 * @covers XSLTBenchmarking\TestsGenerator\Test::setTemplatePath
	 */
	public function testTemplatePathWrong($name)
	{
		file_put_contents($this->setDirSep(__DIR__ . '/' . $name), '');

		$test = new Test('Foo');

		try {
			$test->setTemplatePath($this->setDirSep(__DIR__ . '/' . $name));
			$this->fail();
		} catch (\XSLTBenchmarking\InvalidArgumentException $e) {
			$this->assertTrue(TRUE);
		}

		unlink($this->setDirSep(__DIR__ . '/' . $name));
	}


	/**
	 * provider for testTemplatePathWrong1
	 */
	public function providerTemplatePathWrong()
	{
		return array(
			array('Lorem ipsum'),
			array('Lorem_ipsum'),
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
		mkdir($this->setDirSep(__DIR__ . '/root'));
		mkdir($this->setDirSep(__DIR__ . '/root/path'));
		file_put_contents($this->setDirSep(__DIR__ . '/root/path/template.tpl.xslt'), '');

		$test = new Test('Foo');
		$test->setTemplatePath(__DIR__ . '/root/path/template.tpl.xslt');
		$this->assertEquals('template.xslt', $test->getXsltName());

		unlink($this->setDirSep(__DIR__ . '/root/path/template.tpl.xslt'));
		rmdir($this->setDirSep(__DIR__ . '/root/path'));
		rmdir($this->setDirSep(__DIR__ . '/root'));
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::getXsltPath
	 */
	public function testGetXsltPath()
	{
		mkdir($this->setDirSep(__DIR__ . '/root'));
		mkdir($this->setDirSep(__DIR__ . '/root/path'));
		mkdir($this->setDirSep(__DIR__ . '/root/path/lorem'));
		file_put_contents($this->setDirSep(__DIR__ . '/root/path/lorem/template.tpl.xslt'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/root/path/lorem/myPrefix.template.tpl.xslt'), '');

		$test = new Test('Foo Bar');
		$test->setPath(__DIR__ . '/root/path');

		$test->setTemplatePath(__DIR__ . '/root/path/lorem/template.tpl.xslt');
		$this->assertEquals($this->setDirSep(__DIR__ . '/root/path/foo-bar/template.xslt'), $test->getXsltPath());

		$test->setTemplatePath(__DIR__ . '/root/path/lorem/myPrefix.template.tpl.xslt');
		$this->assertEquals($this->setDirSep(__DIR__ . '/root/path/foo-bar/myPrefix.template.xslt'), $test->getXsltPath());

		unlink($this->setDirSep(__DIR__ . '/root/path/lorem/template.tpl.xslt'));
		unlink($this->setDirSep(__DIR__ . '/root/path/lorem/myPrefix.template.tpl.xslt'));
		rmdir($this->setDirSep(__DIR__ . '/root/path/lorem'));
		rmdir($this->setDirSep(__DIR__ . '/root/path'));
		rmdir($this->setDirSep(__DIR__ . '/root'));
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
		$foo1 = $this->setDirSep(__DIR__ . '/foo1.xml');
		$foo2 = $this->setDirSep(__DIR__ . '/foo2.xml');
		$foo3 = $this->setDirSep(__DIR__ . '/foo3.xml');
		$bar1 = $this->setDirSep(__DIR__ . '/bar1');
		$bar2 = $this->setDirSep(__DIR__ . '/bar2');
		$bar3 = $this->setDirSep(__DIR__ . '/bar3');
		file_put_contents($foo1, '');
		file_put_contents($foo2, '');
		file_put_contents($foo3, '');
		file_put_contents($bar1, '');
		file_put_contents($bar2, '');
		file_put_contents($bar3, '');

		$test = new Test('Foo');
		$test->addFilesPaths(array(__DIR__ . '/foo1.xml' => __DIR__ . '/bar1'));
		$test->addFilesPaths(array(__DIR__ . '/foo2.xml' => __DIR__ . '/bar2', __DIR__ . '/foo3.xml' => __DIR__ . '/bar3'));
		$this->assertEquals(
			array(
				$foo1 => $bar1,
				$foo2 => $bar2,
				$foo3 => $bar3,
			),
			$test->getFilesPaths()
		);

		unlink($foo1);
		unlink($foo2);
		unlink($foo3);
		unlink($bar1);
		unlink($bar2);
		unlink($bar3);
	}


	/**
	 * @covers XSLTBenchmarking\TestsGenerator\Test::addFilesPaths
	 */
	public function testFilesPathsWrongInputExtension()
	{
		$test = new Test('Foo');
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$test->addFilesPaths(array('path/foo1.aaa' => 'path/bar1'));
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
