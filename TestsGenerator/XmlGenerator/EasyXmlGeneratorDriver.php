<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once __DIR__ . '/IXmlGeneratorDriver.php';

/**
 * Driver for generating XML files by \SimpleXMLElement.
 * Generate only list of elements by settings.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class EasyXmlGeneratorDriver implements IXmlGeneratorDriver
{


	/**
	 * Generate xml file
	 *
	 * @param string $outputPath The path of the output xml file
	 * @param array $settings The list of settings specific by selected xml generator
	 *
	 * @return void
	 */
	public function generate($outputPath, array $settings)
	{
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');

		foreach ($settings as $elementName => $count)
		{
			for ($elementIdx = 1; $elementIdx <= $count; $elementIdx++)
			{
				$xml->addChild($elementName, 'Easy element ' . $elementIdx);
			}
		}

		// make indent and new lines
		$dom = dom_import_simplexml($xml)->ownerDocument;
		$dom->formatOutput = true;
		$dom->save($outputPath);
	}


}
