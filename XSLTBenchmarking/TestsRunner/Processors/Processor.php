<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

require_once ROOT . '/Exceptions.php';
require_once ROOT . '/Microtime.php';
require_once LIBS . '/PhpPath/PhpPath.min.php';

use XSLTBenchmarking\Microtime;
use PhpPath\P;


/**
 * Class for parse one template in one processor
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Processor
{


	/**
	 * Path of dirctory containing processors drivers
	 *
	 * @var string
	 */
	private $driversDir;

	/**
	 * Namespace of processors drivers
	 *
	 * @var string
	 */
	private $driversNamespace;

	/**
	 * Path of temporary directory
	 *
	 * @var string
	 */
	private $tmpDir;

	/**
	 * List of available names of processors
	 *
	 * @var array
	 */
	private $available = NULL;

	/**
	 * List of informations about processors
	 *
	 * @var array
	 */
	private $informations = NULL;


	/**
	 * Configure object
	 *
	 * @param string $tmpDir Path of temporary directory
	 * @param string $driversDir Path of dirctory containing processor drivers
	 * @param string $driversNamespace Namespace of processors drivers
	 */
	public function __construct($tmpDir, $driversDir = NULL, $driversNamespace = '\XSLTBenchmarking\TestsRunner\\')
	{
		if (is_null($driversDir))
		{
			$driversDir = P::m(__DIR__, 'Drivers');
		}

		$this->tmpDir = P::mcd($tmpDir);
		$this->driversDir = P::mcd($driversDir);
		$this->driversNamespace = $driversNamespace;
	}


	/**
	 * Return list of available names of processors
	 *
	 * @return array ([name] => AProcessorDriver)
	 */
	public function getAvailable()
	{
		if (!$this->available)
		{
			$this->available = $this->detectAvailable();
		}
		return $this->available;
	}


	/**
	 * Return information about processors
	 *
	 * @return array
	 */
	public function getInformations()
	{
		if (!$this->informations)
		{
			$this->informations = $this->readInformations();
		}
		return $this->informations;
	}


	/**
	 * Run one XSLT transformation in the processor
	 *
	 * @param string $processorName Name of used processor
	 * @param string $templatePath Path of XSLT template
	 * @param string $xmlInputPath Path of XML input file
	 * @param string $outputPath Path of generated output file
	 *
	 * @return array|string List of spend times on transformation|Error message
	 */
	public function run($processorName, $templatePath, $xmlInputPath, $outputPath, $repeating)
	{
		$processors = $this->getAvailable();
		if (!isset($processors[$processorName]))
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('Unknown processor "' . $processorName . '"');
		}

		$processor = $processors[$processorName];

		P::mcf($templatePath);
		P::mcf($xmlInputPath);

		// stylesheet for transformation have to be set in input XML file
		if ($processor->isTemplateSetInInput())
		{
			$xmlInputPath = $this->makeInputWithTemplatePath($xmlInputPath, $templatePath);
		}

		$errorPath = P::m($this->tmpDir, 'transformation.err');
		$command = $this->getCommand($processor, $templatePath, $xmlInputPath, $outputPath, $errorPath);

		$times = array();
		for ($repeatingIdx = 0; $repeatingIdx < $repeating; $repeatingIdx++)
		{
			if (is_file($errorPath))
			{
				throw new \XSLTBenchmarking\InvalidStateException('Error tmp file should not exist "' . $errorPath . '"');
			}

			$timeStart = Microtime::now();
			exec($command);
			$timeEnd = Microtime::now();

			// detect error
			if (is_file($errorPath))
			{
				$error = file_get_contents($errorPath);
				unlink($errorPath);
			}
			else
			{
				$error = '';
			}

			if ($error)
			{
				break;
			}

			// spend time
			$times[] = Microtime::substract($timeEnd, $timeStart);
		}

		if ($error)
		{
			return $error;
		}
		else
		{
			return $times;
		}
	}


	// --- PRIVATE FUNCTIONS ---


	/**
	 * Detect list of available names of processors
	 *
	 * @return array ([name] => AProcessorDriver)
	 */
	private function detectAvailable()
	{
		$driversFiles = scandir($this->driversDir);

		$drivers = array();
		foreach ($driversFiles as $driverFile)
		{
			if (in_array($driverFile, array('AProcessorDriver.php', '.', '..')))
			{
				continue;
			}

			require_once P::m($this->driversDir, $driverFile);
			$className = $this->driversNamespace . substr($driverFile, 0, -4);
			$driver = new $className;

			// driver have to be instance of AProcessorDriver
			if ($driver instanceof AProcessorDriver)
			{
				$drivers[$driver->getName()] = $driver;
			}
		}

		return $drivers;
	}


	/**
	 * Read information about processors
	 *
	 * @return array
	 */
	private function readInformations()
	{
		$informations = array();
		foreach ($this->getAvailable() as $name => $processorDriver)
		{
			$informations[$name] = $processorDriver->getInformations();
		}

		return $informations;
	}


	/**
	 * Make command for excute script with arguments
	 *
	 * Templates substitutions:
	 * [XSLT] = path of XSLT template for transformation
	 * [INPUT] = path of input XML file
	 * [OUTPUT] = path of generated output XML file
	 * [ERROR] = path of file for eventual generated error message
	 * [LIBS] = path of directory containing XSLT processors (libraries, command-line program etc.)
	 *
	 * @param AProcessorDriver $processor Driver for selected processor
	 * @param string $templatePath Path of XSLT template for transformation
	 * @param string $xmlInputPath Path of input XML file
	 * @param string $outputPath Path of generated output XML file
	 * @param string $errorPath Path of file for eventual generated error message
	 *
	 * @return string
	 */
	private function getCommand(AProcessorDriver $processor, $templatePath, $xmlInputPath, $outputPath, $errorPath)
	{
		// get command by OS
		$command = $processor->getCommandTemplate();

		// replace substitutions
		$command = str_replace('[XSLT]', $templatePath, $command);
		$command = str_replace('[INPUT]', $xmlInputPath, $command);
		$command = str_replace('[OUTPUT]', $outputPath, $command);
		$command = str_replace('[ERROR]', $errorPath, $command);
		$command = str_replace('[LIBS]', P::m(LIBS, 'Processors'), $command);

		return $command;
	}


	/**
	 * Add into input XML path of template by directive "<?xml-stylesheet href="[XSLT]" type="text/xml" ..."
	 * and return path of generated file (in temporary directory).
	 *
	 * @param string $xmlInputPath Path of input XML
	 * @param string $templatePath Path of template
	 *
	 * @return string
	 */
	private function makeInputWithTemplatePath($xmlInputPath, $templatePath)
	{
		$content = file_get_contents($xmlInputPath);

		// add template path
		$content = str_replace(
			'<?xml version="1.0" encoding="UTF-8"?>',
			'<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . $templatePath . '" ?>',
			$content
		);

		$xmlInputPath = P::m($this->tmpDir, basename($xmlInputPath));
		file_put_contents($xmlInputPath, $content);

		return $xmlInputPath;
	}


}
