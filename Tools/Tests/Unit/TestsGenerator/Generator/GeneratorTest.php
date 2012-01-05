<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsGenerator\Generator;

require_once ROOT_TOOLS . '/TestsGenerator/Generator.php';

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsGenerator\Generator;

/**
 * GeneratorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\TestsGenerator\Generator::__construct
 */


class GeneratorTest extends TestCase
{

	private $factory;
	private $params;
	private $templating;
	private $paramsTest;

	public function setUp()
	{
		$this->factory = $this->getMock('\XSLTBenchmarking\Factory');
		$this->params = $this->getMock('\XSLTBenchmarking\TestsGenerator\Params');
		$this->templating = $this->getMock('\XSLTBenchmarking\TestsGenerator\Templating');
		$this->paramsTest = $this->getMock('\XSLTBenchmarking\TestsRunner\Params');
	}

	public function testOk()
	{
		$generator = new Generator($this->factory, $this->params, $this->templating, $this->paramsTest, __DIR__, __DIR__);

		$this->assertEquals($this->params, $this->getPropertyValue($generator, 'params'));
		$this->assertEquals($this->templating, $this->getPropertyValue($generator, 'templating'));
		$this->assertEquals($this->paramsTest, $this->getPropertyValue($generator, 'paramsTest'));
		$this->assertEquals(__DIR__, $this->getPropertyValue($generator, 'templatesDirectory'));
		$this->assertEquals(__DIR__, $this->getPropertyValue($generator, 'testsDirectory'));
	}


	public function testBadTemplatedDir()
	{
		$unknown = $this->setDirSep(__DIR__ . '/unknown');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generator = new Generator($this->factory, $this->params, $this->templating, $this->paramsTest, $unknown, __DIR__);
	}


	public function testBadTestsDir()
	{
		$unknown = $this->setDirSep(__DIR__ . '/unknown');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generator = new Generator($this->factory, $this->params, $this->templating, $this->paramsTest, __DIR__, $unknown);
	}


}
