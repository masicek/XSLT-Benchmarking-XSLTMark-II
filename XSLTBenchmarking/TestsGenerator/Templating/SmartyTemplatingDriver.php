<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsGenerator;

require_once LIBS . '/Smarty/Smarty.class.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once __DIR__ . '/ITemplatingDriver.php';
require_once ROOT . '/Exceptions.php';


use PhpPath\P;

/**
 * Extend of Smarty for generating XSLT files from template.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SmartyTemplatingDriver extends \Smarty implements ITemplatingDriver
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDirectory The path of the temporary directory
	 */
	public function __construct($tmpDirectory)
	{
		parent::__construct();
		$this->debugging = FALSE;
		$this->caching = FALSE;
		$this->compile_dir = P::m($tmpDirectory, '/');
	}


	/**
	 * Generate the template with specifis variable and save the content into the file
	 *
	 * @param string $templatePath Path of template for generating
	 * @param string $outputPath Path output file
	 * @param array $settings Array of variables for template
	 *
	 * @throws \XSLTBenchmarking\GenerateTemplateException Problem with generating
	 * @return void
	 */
	public function generate($templatePath, $outputPath, array $settings)
	{
		// set variables
		foreach ($settings as $name => $value)
		{
			$this->assign($name, $value);
		}

		// generate xslt
		ob_start();
		try {
			$this->display($templatePath);
		} catch (\Exception $e) {
			throw new \XSLTBenchmarking\GenerateTemplateException('Cannot generate template by Smarty Driver', 0, $e);
		}
		$content = ob_get_clean();

		$content = $this->repareIndent($content);

		if (!file_put_contents($outputPath, $content))
		{// @codeCoverageIgnoreStart
			throw new \XSLTBenchmarking\GenerateTemplateException('Cannot create file "' . $outputFile . '".');
		}// @codeCoverageIgnoreEnd
	}


	/**
	 * Repare indent of output content, for better human reading.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function repareIndent($content)
	{
		// linearize
		$content = preg_replace('/>[\s]*</', '><', $content);

		// make indent
		$contentSimpleXml = new \SimpleXMLElement($content);
		$contentDomXml = dom_import_simplexml($contentSimpleXml)->ownerDocument;
		$contentDomXml->formatOutput = TRUE;
		$content = $contentDomXml->saveXml();

		// better indent
		$content = str_replace("\r\n", "\n", $content);
		$lines = explode("\n", $content);
		$linesNew = array();
		foreach ($lines as $line)
		{
			$indentEndPos = strpos($line, '<');
			$linesNew[] = str_repeat("\t", $indentEndPos / 2) . substr($line, $indentEndPos);
		}
		$content = implode(PHP_EOL, $linesNew);

		// separate each template
		$content = preg_replace('/(\t*<xsl:template)/', PHP_EOL . '$0', $content);
		$content = str_replace('</xsl:stylesheet>', PHP_EOL . '</xsl:stylesheet>', $content);

		return $content;
	}


}
