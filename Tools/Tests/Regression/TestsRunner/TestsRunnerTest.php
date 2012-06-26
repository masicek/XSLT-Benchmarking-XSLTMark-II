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
 * @covers XSLTBenchmarking\RunnerConsole\Runner::getSubresources
 */
class TestsRunnerTest extends TestCase
{


	public function test()
	{
		// used directories
		$baseDir = __DIR__;
		$tests = $this->setDirSep($baseDir . '/Tests');
		$reports = $this->setDirSep($baseDir . '/ReportsGenerated');
		switch (PHP_OS)
		{
			case 'WINNT':
				$reportsExpected = $this->setDirSep($baseDir . '/reportsExpectedWindows.xml');
				break;
			case 'Linux':
				$reportsExpected = $this->setDirSep($baseDir . '/reportsExpectedLinux.xml');
				break;
		}
		$tmp = $this->setDirSep($baseDir . '/Tmp');

		// simulate arguments for generating tests
		$this->setArguments(
			'-r ' .
			'--tests "./Tests" ' .
			'--reports "./ReportsGenerated" ' .
			'--processors saxon655,xsltproc1126,sablotron103cmd ' .
			'--processors-exclude xsltproc1126 ' .
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

		$path = str_replace('\\', '\\\\', $reportFile);
		$path = str_replace('/', '\\/', $path);
		$this->assertRegExp('/Reports of tests are in "' . $path . '"/', $output);

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

		// check memory (between), check that 'sumMemory' >= 'avgMemory'
		preg_match_all('/sumMemory="[^"]+"/', $report, $sumMemory);
		preg_match_all('/avgMemory="[^"]+"/', $report, $avgMemory);

		$sumMemory = $sumMemory[0];
		$avgMemory = $avgMemory[0];

		$this->assertEquals(count($sumMemory), count($avgMemory));

		for ($memoryIdx = 0; $memoryIdx < count($sumMemory); $memoryIdx++)
		{
			$sumMemory = str_replace('sumMemory="', '', $sumMemory[$memoryIdx]);
			$sumMemory = str_replace('"', '', $sumMemory);
			$avgMemory = str_replace('avgMemory="', '', $avgMemory[$memoryIdx]);
			$avgMemory = str_replace('"', '', $avgMemory);

			$this->assertGreaterThan($avgMemory, $sumMemory, $sumMemory . ' is not greater then ' . $avgMemory . ' (idx="' . $memoryIdx . '")');
		}

		$report = preg_replace('/Time="[^"]+"/', 'Time="..."', $report);
		$report = preg_replace('/Memory="[^"]+"/', 'Memory="..."', $report);
		$report = preg_replace('/-[0-9]{10}-[0-9]{6}.xml/', '-..........-.......xml', $report);
		$report = str_replace(__DIR__, '__PATH__', $report);
		$report = str_replace(str_replace('\\', '/', __DIR__), '__PATH__', $report);
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
