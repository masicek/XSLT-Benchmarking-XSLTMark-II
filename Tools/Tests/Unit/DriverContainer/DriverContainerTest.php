<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark\DriversContainer;

require_once __DIR__ . '/Foo/Foo.php';

use \Tests\XSLTBenchmark\TestCase;

/**
 * DriversContainerTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmark\DriversContainer::__construct
 * @covers XSLTBenchmark\DriversContainer::__call
 * @covers XSLTBenchmark\DriversContainer::getDriversDirectory
 * @covers XSLTBenchmark\DriversContainer::getDriversNamespace
 * @covers XSLTBenchmark\DriversContainer::getDriversNamesSuffix
 */
class DriversContainerTest extends TestCase
{


	public function testFirstFooDriver()
	{
		$foo = new Foo('first');
		$this->assertInstanceOf('\XSLTBenchmark\DriversContainer', $foo);
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\Foo', $foo);

		$driver = $this->getPropertyValue($foo, 'driver');
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\IFooDriver', $driver);
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\FirstFooDriver', $driver);

		$this->assertEquals('First::method1', $foo->methodOne());
		$this->assertEquals('First::method2: lorem ipsum', $foo->methodTwo('lorem', 'ipsum'));

		$this->setExpectedException('\XSLTBenchmark\UnknownMethodException');
		$foo->methodUnknown();
	}


	public function testSecondFooDriver()
	{
		$foo = new Foo('second');
		$this->assertInstanceOf('\XSLTBenchmark\DriversContainer', $foo);
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\Foo', $foo);

		$driver = $this->getPropertyValue($foo, 'driver');
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\IFooDriver', $driver);
		$this->assertInstanceOf('\Tests\XSLTBenchmark\DriversContainer\SecondFooDriver', $driver);

		$this->assertEquals('Second::method1', $foo->methodOne());
		$this->assertEquals('Second::method2: lorem ipsum', $foo->methodTwo('lorem', 'ipsum'));

		$this->setExpectedException('\XSLTBenchmark\UnknownMethodException');
		$foo->methodUnknown();
	}


}
