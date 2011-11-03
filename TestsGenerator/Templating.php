<?php

namespace XSLTBenchmark\TestsGenerator;


require_once __DIR__ . '/../Libs/Smarty/Smarty.class.php';


/**
 * Extend of Smarty for better work with them
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Templating extends \Smarty
{


	/**
	 * Object configuration
	 *
	 * @param string $tmpDir The path of the temporary directory
	 */
	public function __construct($tmpDir)
	{
		parent::__construct();
		$this->debugging = FALSE;
		$this->caching = FALSE;
		$this->compile_dir = $tmpDir;
	}


	/**
	 * Generate the template with specifis variable and save the content into the file
	 *
	 * @param string $templatePath Path of template for generating
	 * @param array $variables Array of variables for template
	 * @param string $outputPath Path output file
	 */
	public function generate($templatePath, $variables, $outputPath)
	{
		// set variables
		foreach ($variables as $name => $value)
		{
			$this->assign($name, $value);
		}

		// generate xslt
		ob_start();
		$this->display($templatePath);
		$content = ob_get_clean();

		if (!file_put_contents($outputPath, $content))
		{
			throw new \Exception('Cannot create file "' . $outputFile . '".');
		}
	}


}
