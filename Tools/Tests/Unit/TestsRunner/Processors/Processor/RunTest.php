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
 * @covers \XSLTBenchmarking\TestsRunner\Processor::run
 * @covers \XSLTBenchmarking\TestsRunner\Processor::getCommand
 */
class RunTest extends TestCase
{


	private $processor;


	public function setUp()
	{
		mkdir(__DIR__ . '/test');
		file_put_contents(__DIR__ . '/test/processorOK.php', '<?php sleep(1); echo "OK";');
		file_put_contents(__DIR__ . '/test/processorError.php', '<?php echo "Error message";');
		file_put_contents(__DIR__ . '/test/processorNoReturn.php', '<?php ');

		$this->processor = new Processor(__DIR__ . '/test');
	}


	public function tearDown()
	{
		unlink(__DIR__ . '/test/processorOK.php');
		unlink(__DIR__ . '/test/processorError.php');
		unlink(__DIR__ . '/test/processorNoReturn.php');
		rmdir(__DIR__ . '/test');
	}


	public function testUnknownProcessor()
	{
		$this->setExpectedException('\XSLTBenchmarking\InvalidArgumentException');
		$this->processor->run('unknown', __FILE__, __FILE__, 'output', 111);
	}


	public function testBadTemplatePath()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->processor->run('processorOK', 'unknown', __FILE__, 'output', 111);
	}


	public function testBadXmlInputPath()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->processor->run('processorOK', __FILE__, 'unknown', 'output', 111);
	}


	public function testSetErrorTranformation()
	{
		$return = $this->processor->run('processorError', __FILE__, __FILE__, 'output', 111);
		$this->assertEquals('Error message', $return);
	}


	public function testUnknownErrorTranformation()
	{
		$return = $this->processor->run('processorNoReturn', __FILE__, __FILE__, 'output', 111);
		$this->assertEquals('Unknown error', $return);
	}


	public function testOk()
	{
		$returnTimes = $this->processor->run('processorOK', __FILE__, __FILE__, 'output', 3);

		$this->assertTrue(is_array($returnTimes));
		$this->assertEquals(3, count($returnTimes));

		// all times are greated then one second
		$this->assertGreaterOneSecondInMicrotime($returnTimes[0]);
		$this->assertGreaterOneSecondInMicrotime($returnTimes[1]);
		$this->assertGreaterOneSecondInMicrotime($returnTimes[2]);
	}


	private function assertGreaterOneSecondInMicrotime($time)
	{
		$compare = bccomp($time, '1.000000', 6);
		$this->assertEquals(1, $compare);
	}

}
