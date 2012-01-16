<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Controlor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Controlor;

require_once ROOT_TOOLS . '/TestsRunner/Controlor.php';

/**
 * SortAttributesTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Controlor::sortAttributes
 */
class SortAttributesTest extends TestCase
{


	/**
	 * @dataProvider provider
	 */
	public function test($matches, $outputExpected)
	{
		$controlor = new Controlor();

		$method = new \ReflectionMethod('\XSLTBenchmarking\TestsRunner\Controlor', 'sortAttributes');
		$method->setAccessible(TRUE);
		$output = $method->invokeArgs($controlor, array($matches));

		$this->assertEquals($outputExpected, $output);
	}


	public function provider()
	{
		return array(
			// empty element
			array(array(
				'<element>'),
				'<element>'),
			array(array(
				'<element/>'),
				'<element/>'),
			// elemennt with one attribute
			array(array(
				'<element attribute1="123 Lorem ipsum">'),
				'<element attribute1="123 Lorem ipsum">'),
			array(array(
				'<element attribute1="123 Lorem ipsum"/>'),
				'<element attribute1="123 Lorem ipsum"/>'),
			// element with two ordered elements
			array(array(
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor">'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor">'),
			array(array(
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor"/>'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor"/>'),
			// element with two unordered elements
			array(array(
				'<element attribute2="456 Lorem ipsum    dolor" attribute1="123 Lorem ipsum  ">'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor">'),
			array(array(
				'<element attribute2="456 Lorem ipsum    dolor" attribute1="123 Lorem ipsum  "/>'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor"/>'),
			// element with three unordered elements
			array(array(
				'<element attribute2="456 Lorem ipsum    dolor" attribute3="789 000" attribute1="123 Lorem ipsum  ">'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor" attribute3="789 000">'),
			array(array(
				'<element attribute2="456 Lorem ipsum    dolor" attribute3="789 000" attribute1="123 Lorem ipsum  "/>'),
				'<element attribute1="123 Lorem ipsum  " attribute2="456 Lorem ipsum    dolor" attribute3="789 000"/>'),
		);
	}


}
