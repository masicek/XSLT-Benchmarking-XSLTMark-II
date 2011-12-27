<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\XmlParamsDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\XmlParamsDriver;

require_once ROOT_TOOLS . '/TestsGenerator/Params/XmlParamsDriver.php';

/**
 * WrongXmlFormatTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\XmlParamsDriver::__construct
 */
class WrongXmlFormatTest extends TestCase
{

	private $tmp;


	public function setUp()
	{
		$this->tmp = $this->setDirSep(__DIR__ . '/tmp.xml');
	}


	public function tearDown()
	{
		if (is_file($this->tmp))
		{
			unlink($this->tmp);
		}
	}


	/**
	 * For controll, that paramsWrong.xml is OK before changing in self::testWrong
	 */
	public function testCheck()
	{
		$driver = new XmlParamsDriver($this->setDirSep(__DIR__ . '/paramsWrong.xml'), __DIR__);
		$this->assertTrue(TRUE);
	}


	/**
	 * @dataProvider providerWrong
	 */
	public function testWrong($search, $replacement)
	{
		// make wrong copy of params file
		$content = file_get_contents($this->setDirSep(__DIR__ . '/paramsWrong.xml'));
		$content = str_replace($search, $replacement, $content);
		file_put_contents($this->tmp, $content);

		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$driver = new XmlParamsDriver($this->tmp, __DIR__);
	}


	public function providerWrong()
	{
		return array(
			array('tests', 'Xtests'),
			array('name="Modify element"', ''),
			array('name="Modify element"', 'name=""'),
			array('template="test.tpl.xslt"', ''),
			array('template="test.tpl.xslt"', 'template=""'),
			array('templatingType="smarty"', ''),
			array('templatingType="smarty"', 'templatingType="unknown"'),
			array('files', 'Xfiles'),
			array('name="Rename"', 'name=""'),
			array('input="many"', 'input="unknown"'),
			array('name="action"', 'name=""'),
			array('generator="easy"', 'generator="unknown"'),
			array('<setting name="testName">20</setting>', ''),
			array('<file input="one" output="manyNew" />', ''),
			array('name="Remove"', 'name="Rename"'),
			array('setting name="newName"', 'setting name="action"'),
			array('setting name="testNameTmp"', 'setting name="testName"'),
		);
	}

}
