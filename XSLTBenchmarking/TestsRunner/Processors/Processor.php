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
 * Class for parse one template in one processor
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Processor
{

	/**
	 * Scale of time decimal precision
	 * @todo maby set as comannd-line option
	 */
	const SCALE = 6;


	/**
	 * Root directory of scripts for runnig processors
	 *
	 * @var string
	 */
	private $scriptsDir = NULL;

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
	 * List of prefixes for runnig processors scripts from command-line
	 *
	 * @var array ([extension of script] => [prefix of command])
	 */
	private $processorsPrefixes = array(
		'php' => 'php ',
		'jar' => 'java -jar ',
	);


	/**
	 * Configure object
	 *
	 * @param string $scriptsDir Root directory of scripts for runnig processors
	 */
	public function __construct($scriptsDir = __DIR__)
	{
		$this->scriptsDir = P::mcd($scriptsDir);
	}


	/**
	 * Return list of available names of processors
	 *
	 * @return array ([name] => [name with extension])
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
	 * Run one XSLT transformation in the processor
	 *
	 * @param string $processor Name of used processor
	 * @param string $templatePath Path of XSLT template
	 * @param string $xmlInputPath Path of XML input file
	 * @param string $outputPath Path of generated output file
	 *
	 * @return array|string List of spend times on transformation|Error message
	 */
	public function run($processor, $templatePath, $xmlInputPath, $outputPath, $repeating)
	{
		$processors = $this->getAvailable();
		if (!isset($processors[$processor]))
		{
			throw new \XSLTBenchmarking\InvalidArgumentException('Unknown processor "' . $processor . '"');
		}

		P::mcf($templatePath);
		P::mcf($xmlInputPath);

		$procesorScript = P::m($this->scriptsDir, $processors[$processor]);
		$command = $this->getCommand($procesorScript, array($templatePath, $xmlInputPath, $outputPath));

		$times = array();
		for ($repeatingIdx = 0; $repeatingIdx < $repeating; $repeatingIdx++)
		{
			$timeStart = $this->getMicrotime();
			exec($command, $output);
			$timeEnd = $this->getMicrotime();

			if (!isset($output[0]) || $output[0] !== 'OK')
			{
				break;
			}

			// spend time
			$times[] = $this->substractMicrotime($timeEnd, $timeStart);
		}

		if (!isset($output[0]))
		{
			return 'Unknown error';
		}
		elseif ($output[0] !== 'OK')
		{
			return implode(PHP_EOL, $output);
		}
		else
		{
			return $times;
		}
	}


	/**
	 * Return information about processors
	 *
	 * @return array [name] => ('fullName' => [fullName], 'link' => [link], 'verions' => [version])
	 */
	public function getInformations()
	{
		if (!$this->informations)
		{
			$this->informations = $this->readInformations();
		}
		return $this->informations;
	}


	// --- PRIVATE FUNCTIONS ---


	/**
	 * Detect list of available names of processors
	 *
	 * @return array ([name] => [name with extension])
	 */
	private function detectAvailable()
	{
		$files = scandir($this->scriptsDir);

		foreach ($files as $file)
		{
			if (in_array($file, array(basename(__FILE__), '.', '..', 'README')))
			{
				continue;
			}

			// OS filter
			// @codeCoverageIgnoreStart
			$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			if (!in_array(PHP_OS, array('WINNT', 'Linux')))
			{
				throw new \XSLTBenchmarking\InvalidStateException('Unknown OS "' . PHP_OS . '"');
			}
			if (PHP_OS == 'WINNT' && $extension == 'sh')
			{
				continue;
			}
			if (PHP_OS == 'Linux' && $extension == 'bat')
			{
				continue;
			}
			// @codeCoverageIgnoreEnd

			$nameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
			$available[$nameWithoutExtension] = $file;
		}

		return $available;
	}


	/**
	 * Read information about processors
	 *
	 * @return array [name] => ('fullName' => [fullName], 'link' => [link], 'verions' => [version])
	 */
	private function readInformations()
	{
		$informations = array();
		$processors = $this->getAvailable();
		foreach ($processors as $processorName => $processorFile)
		{
			// each script return fullName, link and version on separate lines
			$procesorScript = P::m($this->scriptsDir, $processorFile);
			$command = $this->getCommand($procesorScript, array('information'));
			$information = array();
			exec($command, $information);

			$informations[$processorName] = array(
				'fullName' => $information[0],
				'link' => $information[1],
				'version' => $information[2],
			);
		}

		return $informations;
	}


	/**
	 * Make command for excute script with arguments and prepand needed prefix
	 *
	 * @param string $script Script path
	 * @param array $arguments List of arguments
	 *
	 * @return string
	 */
	private function getCommand($script, array $arguments)
	{
		$prefix = '';
		$scriptExtension = strtolower(pathinfo($script, PATHINFO_EXTENSION));
		if (isset($this->processorsPrefixes[$scriptExtension]))
		{
			$prefix = $this->processorsPrefixes[$scriptExtension];
		}

		$command = $prefix . $script . ' ' . implode(' ', $arguments) . ' ' . P::m(LIBS, 'Processors/Saxon');

		return $command;
	}


	// --- TIME FUNCTIONS ---


	/**
	 * Get current time stamp with sufficient precision
	 *
	 * @return string
	 */
	private function getMicrotime()
	{
		list($usec, $sec) = explode(' ', microtime());
		return bcadd($sec, $usec, self::SCALE);
	}


	/**
	 * Get substrast of time stamps with sufficient precision
	 *
	 * @param string $leftOperand Left operand
	 * @param string $rightOperand Right operand
	 *
	 * @return string = $leftOperand - $rightOperand
	 */
	private function substractMicrotime($leftOperand, $rightOperand)
	{
		return bcsub($leftOperand, $rightOperand, self::SCALE);
	}


}
