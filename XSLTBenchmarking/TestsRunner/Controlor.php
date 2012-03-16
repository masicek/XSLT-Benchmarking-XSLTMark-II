<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once ROOT . '/Exceptions.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use PhpPath\P;

/**
 * Class for control that two files has same content.
 * For XML, HTML, XHTML etc. do normalize both files before comapring.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Controlor
{


	/**
	 * Check, if set files have same content.
	 * For XML, HTML, XHTML etc. do normalize both files before comapring.
	 *
	 * @param string $filePath1 Path of the first file
	 * @param string $filePath2 Path of the second file
	 *
	 * @return bool
	 */
	public function isSame($filePath1, $filePath2)
	{
		P::mcf($filePath1);
		P::mcf($filePath2);

		$content1 = file_get_contents($filePath1);
		$content2 = file_get_contents($filePath2);

		// unify EOL
		$content1 = str_replace("\r\n", "\n", $content1);
		$content2 = str_replace("\r\n", "\n", $content2);

		// normalize
		$extension1 = strtolower(pathinfo($filePath1, PATHINFO_EXTENSION));
		if ($extension1 == 'xml')
		{
			$content1 = $this->normalizeXml($content1);
		}
		else
		{
			// unify possible declaration in "non XML files"
			$content1 = $this->unifyDeclaration($content1);
		}

		$extension2 = strtolower(pathinfo($filePath2, PATHINFO_EXTENSION));
		if ($extension2 == 'xml')
		{
			$content2 = $this->normalizeXml($content2);
		}
		else
		{
			// unify possible declaration in "non XML files"
			$content2 = $this->unifyDeclaration($content2);
		}

		return $content1 == $content2;
	}


	/**
	 * If content is XML, then return normalized XML input content.
	 *		- remove insignificant whitespaces
	 *		- sort attributes alphabetical
	 * If content is not XML, return input content.
	 *
	 * @param string $inputContent Normalizing content
	 *
	 * @return string
	 */
	private function normalizeXml($inputContent)
	{
		try
		{
			$inputXml = new \SimpleXMLElement($inputContent);
		}
		catch (\Exception $e)
		{
			// input is not XML
			return $inputContent;
		}

		// normalize empty atribute ('att' => 'att=""')
		// TODO

		// remove insignificant whitespaces
		// simplier empty elements ('<el></el>' => '<el/>')
		// simplier attributes ('att   =  "aaa"    att2="bbb"' => 'att="aaa" att2="bbb"')
		$dom = new \DOMDocument();
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXml($inputContent);
		$outputContent = $dom->saveXML();

		// sort attributes
		$outputContent = preg_replace_callback('#<[^/?][^>]*>#', array($this, 'sortAttributes'), $outputContent);

		$outputContent = $this->unifyDeclaration($outputContent);

		return $outputContent;
	}


	/**
	 * Unify declaration of content. If any declaration are not in content, return unchanged it.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private function unifyDeclaration($content)
	{
		$outputContent = $content;
		$declarationLast = strpos($outputContent, '?>');
		if ($declarationLast !== FALSE)
		{
			$declaration = substr($outputContent, 0, $declarationLast);
			$declarationUnify = trim($declaration);
			$declarationUnify = strtolower($declarationUnify);
			$outputContent = $declarationUnify . substr($outputContent, $declarationLast);
		}

		return $outputContent;
	}


	/**
	 * Normalize element
	 *		- remove insignificant whitespaces
	 *		- sort attributes alphabetical
	 *
	 * @param array $matches The first value is parsed element
	 *
	 * @return string
	 */
	private function sortAttributes($matches)
	{
		$input = $matches[0];

		// begin
		$beginLast = strpos($input, ' ');
		$beginLast = $beginLast ?: strpos($input, '/>');
		$beginLast = $beginLast ?: strpos($input, '>');
		$begin = substr($input, 0, $beginLast);

		// end
		$endFirst = strrpos($input, '/>');
		$endFirst = $endFirst ?: strrpos($input, '>');
		$end = substr($input, $endFirst);

		// attributes
		$attributes = $input;
		$attributes = str_replace($begin, '', $attributes);
		$attributes = str_replace($end, '', $attributes);
		if ($attributes)
		{
			preg_match_all('/[^ ]+="[^"]*"/', $attributes, $matches);
			$attributes = $matches[0];
			$attributes = array_filter($attributes);
			sort($attributes);
			$attributes = implode(' ', $attributes);
		}

		if ($attributes)
		{
			$output = $begin . ' ' . $attributes . $end;
		}
		else
		{
			$output = $begin . $end;
		}

		return $output;
	}


}
