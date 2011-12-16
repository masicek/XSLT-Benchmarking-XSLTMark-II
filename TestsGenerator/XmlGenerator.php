<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark\TestsGenerator;

require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Object for generating xml files by different xml generator.
 * Xml generators are expected in '../Libs/XmlGenrators/'.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class XmlGenerator
{


	/**
	 * Generate xml file by selected xml generator
	 *
	 * @param string $type Selected xml generator
	 * @param string $outputPath The path of the output xml file
	 * @param array $settings The list of settings specific by selected xml generator
	 *
	 * @return void
	 */
	public function generate($type, $outputPath, array $settings)
	{
		$rootDirectory = P::m(LIBS, 'XmlGenerators');

		switch ($type)
		{
			case 'testGenerator':
				$script = P::m($rootDirectory, 'TestGenerator/run.php');
				$command = sprintf('php %s -o %s -c %d -e %s',
					$script,
					$outputPath,
					$settings['elementNumber'],
					$settings['elementName']
				);
				break;

			default:
				throw new Exception('Unknown xml generator "' . $type . '"');
				break;
		}

		passthru($command);
	}


}
