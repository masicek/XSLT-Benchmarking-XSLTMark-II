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

/**
 * TemplatingTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TemplatingTest extends TestCase
{


	public function testSimple()
	{
		$templating = new Templating('simple', __DIR__);
		$driver = $this->getPropertyValue($templating, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\ITemplatingDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\SimpleTemplatingDriver', $driver);
	}


	public function testSmarty()
	{
		$templating = new Templating('smarty', __DIR__);
		$driver = $this->getPropertyValue($templating, 'driver');
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\ITemplatingDriver', $driver);
		$this->assertInstanceOf('\XSLTBenchmarking\TestsGenerator\SmartyTemplatingDriver', $driver);
	}


}
