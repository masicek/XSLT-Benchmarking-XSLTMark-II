<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/../Directory.php';


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
		$rootDirectory = Directory::make(__DIR__, '/../../Libs/XmlGenerators');

		switch ($type)
		{
			case 'testGenerator':
				$script = Directory::make($rootDirectory, './TestGenerator/run.php');
				$command = sprintf('php %s -o %s -c %d', $script, $outputPath, $settings['elementNumber']);
				break;

			default:
				throw new Exception('Unknown xml generator "' . $type . '"');
				break;
		}

		exec($command);
	}


}
