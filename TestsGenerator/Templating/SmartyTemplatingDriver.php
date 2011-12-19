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
		$this->display($templatePath);
		$content = ob_get_clean();

		if (!file_put_contents($outputPath, $content))
		{// @codeCoverageIgnoreStart
			throw new \XSLTBenchmarking\GenerteTemplateException('Cannot create file "' . $outputFile . '".');
		}// @codeCoverageIgnoreEnd
	}


}
