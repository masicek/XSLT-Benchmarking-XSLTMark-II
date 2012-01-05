<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\DriversContainer;

require_once __DIR__ . '/Foo/Foo.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * DriversContainerTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\DriversContainer::__construct
 * @covers XSLTBenchmarking\DriversContainer::setDriver
 * @covers XSLTBenchmarking\DriversContainer::__call
 * @covers XSLTBenchmarking\DriversContainer::getDriversDirectory
 * @covers XSLTBenchmarking\DriversContainer::getDriversNamespace
 * @covers XSLTBenchmarking\DriversContainer::getDriversNamesSuffix
 */
class DriversContainerTest extends TestCase
{


	public function testFirstFooDriver()
	{
		$foo = new Foo('first param', 'second param');
		$this->assertInstanceOf('\XSLTBenchmarking\DriversContainer', $foo);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\Foo', $foo);
		$this->assertNull($this->getPropertyValue($foo, 'driver'));

		$foo->setDriver('first');
		$driver = $this->getPropertyValue($foo, 'driver');
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\IFooDriver', $driver);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\FirstFooDriver', $driver);

		$this->assertEquals('First::method1 (first param)', $foo->methodOne());
		$this->assertEquals('First::method2: (second param) lorem ipsum', $foo->methodTwo('lorem', 'ipsum'));

		$this->setExpectedException('\XSLTBenchmarking\UnknownMethodException');
		$foo->methodUnknown();
	}


	public function testSecondFooDriver()
	{
		$foo = new Foo('first param', 'second param');
		$this->assertInstanceOf('\XSLTBenchmarking\DriversContainer', $foo);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\Foo', $foo);
		$this->assertNull($this->getPropertyValue($foo, 'driver'));

		$foo->setDriver('second');
		$driver = $this->getPropertyValue($foo, 'driver');
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\IFooDriver', $driver);
		$this->assertInstanceOf('\Tests\XSLTBenchmarking\DriversContainer\SecondFooDriver', $driver);

		$this->assertEquals('Second::method1', $foo->methodOne());
		$this->assertEquals('Second::method2: lorem ipsum', $foo->methodTwo('lorem', 'ipsum'));

		$this->setExpectedException('\XSLTBenchmarking\UnknownMethodException');
		$foo->methodUnknown();
	}


	public function testSecondFooDriverTwice()
	{
		$foo = new Foo(NULL, NULL);
		$this->assertNull($this->getPropertyValue($foo, 'driver'));

		$foo->setDriver('second');
		$driver1 = $this->getPropertyValue($foo, 'driver');

		$foo->setDriver('second');
		$driver2 = $this->getPropertyValue($foo, 'driver');

		$this->assertNotSame($driver1, $driver2);
	}


}
