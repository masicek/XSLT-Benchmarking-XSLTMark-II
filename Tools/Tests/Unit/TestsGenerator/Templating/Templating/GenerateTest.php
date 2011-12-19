<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Templating;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Templating;

require_once ROOT_TOOLS . '/TestsGenerator/Templating/Templating.php';
require_once ROOT_TOOLS . '/TestsGenerator/Templating/ITemplatingDriver.php';

/**
 * GenerateTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\Templating::generate
 */
class GenerateTest extends TestCase
{


	public function testOk()
	{
		$templating = new Templating('simple', __DIR__);

		// mocked driver
		$driver = \Mockery::mock('\XSLTBenchmarking\TestsGenerator\ITemplatingDriver');
		$driver->shouldReceive('generate')->with(__FILE__, 'output/path', array('foo', 'bar' => 'car'));
		$this->setPropertyValue($templating, 'driver', $driver);

		$templating->generate(__FILE__, 'output/path', array('foo', 'bar' => 'car'));
		$this->assertTrue(TRUE);
	}


	public function testBadTemapltePath()
	{
		$templating = new Templating('simple', __DIR__);
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$templating->generate('./foo.php', 'output/path');
	}


}
