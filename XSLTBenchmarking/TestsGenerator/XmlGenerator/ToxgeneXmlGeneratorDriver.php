<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once __DIR__ . '/IXmlGeneratorDriver.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Exceptions.php';

use PhpPath\P;

/**
 * Driver for generating XML files by ToXGene.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class ToxgeneXmlGeneratorDriver implements IXmlGeneratorDriver
{


	/**
	 * It is the minimum heap size for the java runtime
	 */
	const MIN_HEAP = 64000000;

	/**
	 * Default random seed for running ToXGene
	 */
	const TOXGENE_DEFAULT_SEED = 123456789;

	/**
	 * Value of PHP_OS for Windows
	 */
	const OS_WIN = 'WINNT';

	/**
	 * Value of PHP_OS for Linux
	 */
	const OS_LINUX = 'Linux';


	private $tmpDir;

	/**
	 * Object configuration
	 *
	 * @param string $tmpDir The path of the temporary directory
	 */
	public function __construct($tmpDir)
	{
		// ToXGene option '-d' have to get directory without end '/' eventually '\'
		P::mcd($tmpDir, '/');
		$this->tmpDir = substr($tmpDir, 0, -1);
	}


	/**
	 * Generate xml file
	 *
	 * @param string $outputPath The path of the output xml file
	 * @param array $settings The list of settings specific by selected xml generator
	 *	- template = ToXGene template
	 *	- document = select witch tox-document will by selected (default = first)
	 *	- indent = set/unset (0/1) indent of generated XML file (default = 1)
	 *	- seed = random seed for generating (default self::TOXGENE_DEFAULT_SEED)
	 *
	 * @return void
	 */
	public function generate($outputPath, $templateDir, array $settings)
	{
		$home = P::m(LIBS, 'XmlGenerators/ToXGene/2.3');
		$toxgene = P::m($home, 'toxgene.jar');
		$xercesImpl = P::m($home, 'Xerces-2.6.2/xercesImpl.jar');
		$xercesApis = P::m($home, 'Xerces-2.6.2/xml-apis.jar');
		$xercesParser = P::m($home, 'Xerces-2.6.2/xmlParserAPIs.jar');

		switch (PHP_OS)
		{
			case self::OS_WIN:
				$java = '"' . P::m(LIBS, 'Java/1.6.0_29/java.exe') . '"';
				$home = '"' . $home . '"';
				$class = '"' . $toxgene . '";"' . $xercesImpl . '";"' . $xercesApis . '";"' . $xercesParser . '"';
				break;

			case self::OS_LINUX:
				// we assume installing java
				$java = 'java';
				$class = $toxgene . ':' . $xercesImpl . ':' . $xercesApis . ':' . $xercesParser;
				break;
		}

		$seed = self::TOXGENE_DEFAULT_SEED;
		if (isset($settings['seed']))
		{
			$seed = $settings['seed'];
		}

		$template = P::m($templateDir, $settings['template']);

		// get output name - default first document, other set name
		if (isset($settings['document']))
		{
			$outputName = $settings['document'];
		}
		else
		{
			preg_match('/<tox-document name="([^"]*)">/', file_get_contents($template), $matches);
			$outputName = $matches[1];
		}

		$params =
			'-s ' . $seed . ' ' .
			'-i "' . $templateDir . '" ' .
			'-d "' . $this->tmpDir . '"'
		;

		$command = $java . ' -Xmx' . self::MIN_HEAP . ' -DToXgene_home=' . $home . ' -classpath ' . $class . ' toxgene.ToXgene ' . $params . ' "' . $template . '" 2>&1';

		exec($command, $output);

		// detect error
		$error = '';
		$output = implode(' ', $output);
		$errorPos = strpos($output, 'ERROR');
		if ($errorPos != FALSE)
		{
			$error = substr($output, $errorPos);
		}
		else
		{
			$errorPos = strpos($output, 'Exception');
			if ($errorPos != FALSE)
			{
				$error = substr($output, $errorPos);
			}
		}

		if ($error)
		{
			throw new \XSLTBenchmarking\GenerateXmlException($error);
		}

		$generatedFilePath = P::m($this->tmpDir, $outputName . '.xml');
		if ($generatedFilePath !== $outputPath)
		{
			copy($generatedFilePath, $outputPath);
			unlink($generatedFilePath);
		}

		// make indent (default 'yes')
		if (!isset($settings['indent']) || $settings['indent'] === '1')
		{
			$contentSimpleXml = new \SimpleXMLElement($outputPath, 0, TRUE);
			$contentDomXml = dom_import_simplexml($contentSimpleXml)->ownerDocument;
			$contentDomXml->formatOutput = TRUE;
			$contentDomXml->save($outputPath);
		}
	}


}
