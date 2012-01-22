<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Exceptions;

require_once ROOT_TOOLS . '/Exceptions.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * ExceptionsTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class ExceptionsTest extends TestCase
{

	/**
	 * @covers \XSLTBenchmarking\Exception
	 *
	 * @expectedException \XSLTBenchmarking\Exception
	 * @expectedExceptionMessage Test message
	 */
	public function testException()
	{
		throw new \XSLTBenchmarking\Exception('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\InvalidArgumentException
	 *
	 * @expectedException \XSLTBenchmarking\InvalidArgumentException
	 * @expectedExceptionMessage Test message
	 */
	public function testInvalidArgumentException()
	{
		throw new \XSLTBenchmarking\InvalidArgumentException('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\GenerateTemplateException
	 *
	 * @expectedException \XSLTBenchmarking\GenerateTemplateException
	 * @expectedExceptionMessage Test message
	 */
	public function testGenerateTemplateException()
	{
		throw new \XSLTBenchmarking\GenerateTemplateException('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\UnknownMethodException
	 *
	 * @expectedException \XSLTBenchmarking\UnknownMethodException
	 * @expectedExceptionMessage Test message
	 */
	public function testUnknownMethodException()
	{
		throw new \XSLTBenchmarking\UnknownMethodException('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\CollisionException
	 *
	 * @expectedException \XSLTBenchmarking\CollisionException
	 * @expectedExceptionMessage Test message
	 */
	public function testCollisionException()
	{
		throw new \XSLTBenchmarking\CollisionException('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\InvalidStateException
	 *
	 * @expectedException \XSLTBenchmarking\InvalidStateException
	 * @expectedExceptionMessage Test message
	 */
	public function testInvalidStateException()
	{
		throw new \XSLTBenchmarking\InvalidStateException('Test message');
	}


	/**
	 * @covers \XSLTBenchmarking\UnsupportedOSException
	 */
	public function testUnsupportedOSException()
	{
		$this->setExpectedException(
			'\XSLTBenchmarking\UnsupportedOSException',
			'Unsupported operating system "' . PHP_OS . '"'
		);
		throw new \XSLTBenchmarking\UnsupportedOSException();
	}


}
