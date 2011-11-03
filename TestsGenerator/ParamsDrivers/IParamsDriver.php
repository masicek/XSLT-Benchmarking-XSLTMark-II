<?php

namespace XSLTBenchmark\TestsGenerator;


/**
 * Interface for object for collect params about templates.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
interface IParamsDriver
{

	/**
	 * Object configuration
	 */
	public function __construct($rootDirectory, $paramsFile);


	/**
	 * Return the name of tests collection
	 *
	 * @return string
	 */
	public function getTemplateName();


	/**
	 * Return the path to the template file
	 *
	 * @return string
	 */
	public function getTemplatePath();


	/**
	 * Return the list of paths to files for testing
	 *
	 * @param string $tmpDir Temporary directory for generating xml files
	 *
	 * @return array
	 */
	public function getXmlFilesPaths($tmpDir);


	/**
	 * Return the list of tests with their variables
	 *
	 * @return array
	 */
	public function getTests();


}
