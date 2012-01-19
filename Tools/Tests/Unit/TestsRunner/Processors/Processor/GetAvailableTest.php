<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Processor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Processor;

require_once ROOT_TOOLS . '/TestsRunner/Processors/Processor.php';

/**
 * ProcessorTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getAvailable
 * @covers \XSLTBenchmarking\TestsRunner\Processor::detectAvailable
 */
class GetAvailableTest extends TestCase
{


	public function test()
	{
		mkdir(__DIR__ . '/test');
		file_put_contents(__DIR__ . '/test/processor1.php', '');
		file_put_contents(__DIR__ . '/test/processor2.bat', '');
		file_put_contents(__DIR__ . '/test/processor2.sh', '');

		$processor = new Processor(__DIR__ . '/test');

		switch (PHP_OS)
		{
			case 'WINNT':
				$expectedAvailable = array(
					'processor1' => 'processor1.php',
					'processor2' => 'processor2.bat',
				);
				break;

			case 'Linux':
				$expectedAvailable = array(
					'processor1' => 'processor1.php',
					'processor2' => 'processor2.sh',
				);
		}

		$this->assertEquals($expectedAvailable, $processor->getAvailable());

		unlink(__DIR__ . '/test/processor1.php');
		unlink(__DIR__ . '/test/processor2.bat');
		unlink(__DIR__ . '/test/processor2.sh');
		rmdir(__DIR__ . '/test');
	}


}
