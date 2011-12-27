<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\XmlParamsDriver;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\XmlParamsDriver;

require_once ROOT_TOOLS . '/TestsRunner/Params/XmlParamsDriver.php';

/**
 * WrongXmlFormatTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsRunner\XmlParamsDriver::__construct
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
		$driver = new XmlParamsDriver($this->tmp);
	}


	public function providerWrong()
	{
		return array(
			array('test', 'Xtest'),
			array('name="Test name"', ''),
			array('name="Test name"', 'name=""'),
			array('template="test.xslt"', ''),
			array('template="test.xslt"', 'template=""'),
			array('couple', 'Xcouple'),
			array('input="firstIn.xml"', ''),
			array('input="firstIn.xml"', 'input=""'),
			array('output="firstOut.xml"', ''),
			array('output="firstOut.xml"', 'output=""'),
			array('input="firstIn.xml"', 'input="secondIn.xml"'),
			array(
				'<couple input="firstIn.xml" output="firstOut.xml"/>' . PHP_EOL . "\t" .
				'<couple input="secondIn.xml" output="secondOut.xml"/>',
				''
			),
		);
	}

}
