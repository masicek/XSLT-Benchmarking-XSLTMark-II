<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\Reports;

/**
 * Abstract parent for object for converting reports.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IConvertorDriver
{


	/**
	 * Configure object
	 *
	 * @param string $tmpDir Path of temporary directory
	 */
	public function __construct($tmpDir);


	/**
	 * Convert reports into set format and save it into set directory.
	 *
	 * @param string $inputFile Report file for converting
	 * @param string $outputDir Directory to save generated file
	 *
	 * @return string
	 */
	public function convert($inputFile, $outputDir);


}
