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
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getInformations
 * @covers \XSLTBenchmarking\TestsRunner\Processor::readInformations
 */
class GetInformationsTest extends TestCase
{


	public function test()
	{
		mkdir(__DIR__ . '/test');
		file_put_contents(__DIR__ . '/test/processor1.php',
			'<?php if (isset($argv[1]) && $argv[1] == \'--information\') {echo "name 1\nlink 1\nversion 1";}'
		);
		file_put_contents(__DIR__ . '/test/processor2.php',
			'<?php if (isset($argv[1]) && $argv[1] == \'--information\') {echo "name 2\nlink 2\nversion 2";}'
		);
		file_put_contents(__DIR__ . '/test/README', '');

		$processor = new Processor(__DIR__ . '/test');
		$this->assertEquals(array(
				'processor1' => array(
					'fullName' => 'name 1',
					'link' => 'link 1',
					'version' => 'version 1',
				),
				'processor2' => array(
					'fullName' => 'name 2',
					'link' => 'link 2',
					'version' => 'version 2',
				),
			),
			$processor->getInformations());

		unlink(__DIR__ . '/test/processor1.php');
		unlink(__DIR__ . '/test/processor2.php');
		unlink(__DIR__ . '/test/README');
		rmdir(__DIR__ . '/test');
	}


}
