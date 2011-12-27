<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once LIBS . '/PhpPath/PhpPath.min.php';
require_once ROOT . '/Exceptions.php';

use PhpPath\P;

/**
 * Container for information about one test.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Test
{


	/**
	 * Name of the test
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Path of the XSLT template
	 *
	 * @var string
	 */
	private $templatePath;

	/**
	 * Couples of XML inpiut and expected output files
	 *
	 * @var array ([input] => [expected output], ...)
	 */
	private $couples;


	/**
	 * Set name of the test
	 *
	 * @param string $name Name of the test
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}


	/**
	 * Return name of the test
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Set path of XSLT template
	 *
	 * @param string $templatePath The path of XSLT template
	 *
	 * @return void
	 */
	public function setTemplatePath($templatePath)
	{
		$templatePath = P::mcf($templatePath);

		$extension = pathinfo($templatePath, PATHINFO_EXTENSION);
		if ($extension != 'xsl' && $extension != 'xslt')
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('XSLT template path does not have extension ".xsl" or ".xslt". It has value "' . $templatePath . '"');
		}

		$this->templatePath = $templatePath;
	}


	/**
	 * Return path of XSLT template
	 *
	 * @return string
	 */
	public function getTemplatePath()
	{
		return $this->templatePath;
	}


	/**
	 * Add couples of input and expected output XML files
	 *
	 * @param array $couples ([input] => [expected output], ...)
	 *
	 * @return void
	 */
	public function addCouplesPaths(array $couples)
	{
		foreach ($couples as $input => $expectedOutput)
		{
			$inputExtension = pathinfo($input, PATHINFO_EXTENSION);
			if ($inputExtension != 'xml')
			{
				throw new \XSLTBenchmarking\InvalidArgumentException('XSLT template path does not have extension ".xml". It has value "' . $input . '"');
			}
			$this->couples[P::mcf($input)] = P::mcf($expectedOutput);

		}
	}


	/**
	 * Return couples of input and expected output XML files
	 *
	 * @return array ([input] => [expected output], ...)
	 */
	public function getCouplesPaths()
	{
		return $this->couples;
	}


}
