<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking;

require_once ROOT_TOOLS . '/RunnerConsole/Runner.php';

use \Tests\XSLTBenchmarking\TestCase;

/**
 * TestsRunnerTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers XSLTBenchmarking\RunnerConsole\Runner::__construct
 * @covers XSLTBenchmarking\RunnerConsole\Runner::defineOptions
 * @covers XSLTBenchmarking\RunnerConsole\Runner::run
 * @covers XSLTBenchmarking\RunnerConsole\Runner::runTests
 * @covers XSLTBenchmarking\RunnerConsole\Runner::getDirs
 */
class TestsRunnerTest extends TestCase
{


	public function test()
	{
		// used directories
		$baseDir = __DIR__;
		$tests = $this->setDirSep($baseDir . '/Tests');
		$reports = $this->setDirSep($baseDir . '/ReportsGenerated');
		$reportsExpected = $this->setDirSep($baseDir . '/reportsExpected.xml');
		$tmp = $this->setDirSep($baseDir . '/Tmp');

		// simulate arguments for generating tests
		$this->setArguments(
			'-r ' .
			'--tests "./Tests" ' .
			'--reports "./ReportsGenerated" ' .
			'--processors saxon655,libxslt1123cmd,sablotron103cmd ' .
			'--processors-exclude libxslt1123cmd ' .
			'--repeating 5 ' .
			'--tmp "./Tmp" '
		);

		// check not exitence of report
		$this->assertFalse(is_dir($reports));

		// run runner for runnig tests
		$runner = new \XSLTBenchmarking\RunnerConsole\Runner($baseDir);
		ob_start();
		$runner->run();
		$output = ob_get_clean();

		// check generated report
		$reportsFiles = scandir($reports);
		unset($reportsFiles[array_search('.', $reportsFiles)]);
		unset($reportsFiles[array_search('..', $reportsFiles)]);
		$this->assertEquals(1, count($reports));
		$reportFile = $this->setDirSep($reports . '/' . array_shift($reportsFiles));

		$this->assertRegExp('/Reports of tests are in "' . str_replace('\\', '\\\\', $reportFile) . '"/', $output);

		$report = file_get_contents($reportFile);

		// check times (between), check that 'sumTime' >= 'avgTime'
		preg_match_all('/sumTime="[^"]+"/', $report, $sumTimes);
		preg_match_all('/avgTime="[^"]+"/', $report, $avgTimes);

		$sumTimes = $sumTimes[0];
		$avgTimes = $avgTimes[0];

		$this->assertEquals(count($sumTimes), count($avgTimes));

		for ($timeIdx = 0; $timeIdx < count($sumTimes); $timeIdx++)
		{
			$sumTime = str_replace('sumTime="', '', $sumTimes[$timeIdx]);
			$sumTime = str_replace('"', '', $sumTime);
			$avgTime = str_replace('avgTime="', '', $avgTimes[$timeIdx]);
			$avgTime = str_replace('"', '', $avgTime);

			list($sumSec, $sumUsec) = explode('.', $sumTime);
			list($avgSec, $avgUsec) = explode('.', $avgTime);

			if (!(($sumSec > $avgSec) || ($sumSec == $avgSec && $sumUsec > $avgUsec)))
			{
				$this->fail($sumTime . ' is not greater then ' . $avgTime . ' (idx="' . $timeIdx . '")');
			}
		}

		$report = preg_replace('/Time="[^"]+"/', 'Time="..."', $report);
		$this->assertXmlStringEqualsXmlFile($reportsExpected, $report);


		// remove generated report
		unlink($reportFile);
		rmdir($reports);

		// remove tmp directory
		foreach (scandir($tmp) as $file)
		{
			// remove files safely
			if (preg_match('/^[a-zA-Z]+-[0-9]+-[0-9]+[.]xml$/', $file))
			{
				unlink($this->setDirSep($tmp . '/' . $file));
			}
		}
		rmdir($tmp);
	}


}
