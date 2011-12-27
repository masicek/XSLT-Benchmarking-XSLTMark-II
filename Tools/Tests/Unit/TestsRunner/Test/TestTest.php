<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Test;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Test;

require_once ROOT_TOOLS . '/TestsRunner/Test.php';

/**
 * TestTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestTest extends TestCase
{


	/**
	 * @covers \XSLTBenchmarking\TestsRunner\Test::__construct
	 * @covers \XSLTBenchmarking\TestsRunner\Test::getName
	 */
	public function testTest()
	{
		$test = new Test('Foo');
		$this->assertEquals('Foo', $test->getName());
	}


	/**
	 * @covers \XSLTBenchmarking\TestsRunner\Test::setTemplatePath
	 * @covers \XSLTBenchmarking\TestsRunner\Test::getTemplatePath
	 */
	public function testTemplatePath()
	{
		file_put_contents($this->setDirSep(__DIR__ . '/template.xsl'), '');
		file_put_contents($this->setDirSep(__DIR__ . '/template.xslt'), '');

		$test = new Test('Foo');

		$test->setTemplatePath(__DIR__ . '/template.xsl');
		$this->assertEquals($this->setDirSep(__DIR__ . '/template.xsl'), $test->getTemplatePath());

		$test->setTemplatePath(__DIR__ . '/template.xslt');
		$this->assertEquals($this->setDirSep(__DIR__ . '/template.xslt'), $test->getTemplatePath());

		unlink($this->setDirSep(__DIR__ . '/template.xsl'));
		unlink($this->setDirSep(__DIR__ . '/template.xslt'));
	}


	/**
	 * @covers XSLTBenchmarking\TestsRunner\Test::setTemplatePath
	 */
	public function testTemplatePathNotExistTemplate()
	{
		$test = new Test('Foo');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$test->setTemplatePath($this->setDirSep(__DIR__ . '/unknown.xslt'));
	}


	/**
	 * @dataProvider providerTemplatePathWrong
	 *
	 * @covers XSLTBenchmarking\TestsRunner\Test::setTemplatePath
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
			array('lorem.aaa'),
			array('lorem.aaaxslt'),
			array('loremxslt'),
			array('xslt'),
		);
	}


	/**
	 * @covers \XSLTBenchmarking\TestsRunner\Test::addCouplesPaths
	 * @covers \XSLTBenchmarking\TestsRunner\Test::getCouplesPaths
	 */
	public function testCouplesPaths()
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
		$test->addCouplesPaths(array(__DIR__ . '/foo1.xml' => __DIR__ . '/bar1'));
		$test->addCouplesPaths(array(__DIR__ . '/foo2.xml' => __DIR__ . '/bar2', __DIR__ . '/foo3.xml' => __DIR__ . '/bar3'));
		$this->assertEquals(
			array(
				$foo1 => $bar1,
				$foo2 => $bar2,
				$foo3 => $bar3,
			),
			$test->getCouplesPaths()
		);

		unlink($foo1);
		unlink($foo2);
		unlink($foo3);
		unlink($bar1);
		unlink($bar2);
		unlink($bar3);
	}


	/**
	 * @covers XSLTBenchmarking\TestsRunner\Test::addCouplesPaths
	 */
	public function testCouplesPathsWrongInputExtension()
	{
		$test = new Test('Foo');
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$test->addCouplesPaths(array('path/foo1.aaa' => 'path/bar1'));
	}


}
