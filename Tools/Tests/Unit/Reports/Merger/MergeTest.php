<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\Reports\Merger;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\Reports\Merger;

require_once ROOT_TOOLS . '/Reports/Merger.php';

/**
 * MergeTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\Reports\Merger::merge
 * @covers \XSLTBenchmarking\Reports\Merger::getMergeredReport
 * @covers \XSLTBenchmarking\Reports\Merger::copyChilds
 * @covers \XSLTBenchmarking\Reports\Merger::copyOrReplaceAttributes
 * @covers \XSLTBenchmarking\Reports\Merger::saveMegeredReports
 */
class MergeTest extends TestCase
{


	/**
	 * @dataProvider provider
	 */
	public function test($expectedOutput, array $inputs)
	{
		$merger = new Merger();
		foreach ($inputs as $input)
		{
			$merger->addReportFile($this->setDirSep(__DIR__ . '/FixtureReports/' . $input));
		}

		$output = $merger->merge(__DIR__);

		$this->assertXmlFileEqualsXmlFile(
			$this->setDirSep(__DIR__ . '/FixtureReports/' . $expectedOutput),
			$output
		);

		unlink($output);
	}


	public function provider()
	{
		return array(
			'addInput' => array('expectedAddInput.xml', array('base.xml', 'addInput.xml')),
			'addProcessor' => array('expectedAddProcessor.xml', array('base.xml', 'addProcessor.xml')),
			'addTest' => array('expectedAddTest.xml', array('base.xml', 'addTest.xml')),
			'addTestInput2Processor' => array('expectedAddTestInput2Processor.xml', array('base.xml', 'addTest.xml', 'addInput2.xml', 'addProcessor.xml')),
			'addTestProcessorInput2' => array('expectedAddTestProcessorInput2.xml', array('base.xml', 'addTest.xml', 'addProcessor.xml', 'addInput2.xml')),
		);
	}


}
