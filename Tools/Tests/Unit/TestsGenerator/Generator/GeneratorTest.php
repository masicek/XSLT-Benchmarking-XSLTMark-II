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


	public function testOk()
	{
		$templates = $this->setDirSep(__DIR__ . '/A');
		$tests = $this->setDirSep(__DIR__ . '/B');
		$tmp = $this->setDirSep(__DIR__ . '/C');

		mkdir($templates);
		mkdir($tests);
		mkdir($tmp);

		$generator = new Generator($templates, $tests, $tmp);

		$this->assertEquals($templates, $this->getPropertyValue($generator, 'templatesDirectory'));
		$this->assertEquals($tests, $this->getPropertyValue($generator, 'testsDirectory'));
		$this->assertEquals($tmp, $this->getPropertyValue($generator, 'tmpDirectory'));

		rmdir($templates);
		rmdir($tests);
		rmdir($tmp);
	}


	public function testBadTemplatedDir()
	{
		$unknown = $this->setDirSep(__DIR__ . '/unknown');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generator = new Generator($unknown, __DIR__, __DIR__);
	}


	public function testBadTestsDir()
	{
		$unknown = $this->setDirSep(__DIR__ . '/unknown');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generator = new Generator(__DIR__, $unknown, __DIR__);
	}


	public function testBadTmpDir()
	{
		$unknown = $this->setDirSep(__DIR__ . '/unknown');
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$generator = new Generator(__DIR__, __DIR__, $unknown);
	}


}
