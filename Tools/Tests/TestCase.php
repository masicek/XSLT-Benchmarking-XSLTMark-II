<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmark;


require_once LIBS . '/PhpOptions/PhpOptions.min.php';


/**
 * Extends of PHPUnit TestCase class.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{


	/**
	 * Clean cache of simulated command-line arguments before each test
	 */
	public function setUp()
	{
		$this->setPropertyValue('Arguments', 'PhpOptions\Arguments::$options', NULL);
	}


	/**
	 * Simulate input command-line arguments
	 *
	 * @param string $arguments List of arguments
	 *
	 * @return void
	 */
	protected function setArguments($arguments)
	{
		$arguments = preg_replace('/(' . "\r\n|\t" . ')+/', ' ', $arguments);
		$arguments = trim($arguments);
		$argumentsNew = '';
		$inQuation = FALSE;
		for ($i = 0; $i < strlen($arguments); $i++)
		{
			$char  = $arguments[$i];
			if ($char == '"' && !$inQuation)
			{
				$inQuation = TRUE;
			}
			elseif ($char == '"' && $inQuation)
			{
				$inQuation = FALSE;
			}

			if ($char == ' ' && $inQuation)
			{
				$argumentsNew .= '###SPACE###';
			}
			elseif ($char != '"')
			{
				$argumentsNew .= $char;
			}
		}
		$arguments = $argumentsNew;
		$arguments = preg_replace('/ +/', ' ', $arguments);

		$argv = explode(' ', trim('my_script.php ' . $arguments));

		for ($i = 0; $i < count($argv); $i++)
		{
			$argv[$i] = str_replace('###SPACE###', ' ', $argv[$i]);
		}

		$_SERVER['argv'] = $argv;
	}


	/**
	 * Change value of protected / private property.
	 *
	 * If you want to change the value of private property, which not define the object, but a parent,
	 * you need to specify the form $property "Class::$propertyName", where Class is class, which the property defined.
	 *
	 * Example:
	 * <pre>
	 *  $this->setPropertyValue('Arguments', 'PhpOptions\Arguments::$options', NULL);
	 * </pre>
	 *
	 * @param string|object	$object Neme of class (for static property) or instance of class
	 * @param string $property Name of property
	 * @param mixed $value New value
	 *
	 * @return void
	 */
	protected function setPropertyValue($object, $property, $value)
	{
		if (($pos = strpos($property, '::$')) !== FALSE)
		{
			$class = substr($property, 0, $pos);
			$property = substr($property, $pos + 3);
		}

		$property = $this->getAccessibleProperty(isset($class) ? $class : $object, $property);
		$property->setValue($object, $value);
	}


	/**
	 * Return value of protected / private property.
	 *
	 * If you want to read the value of private property, which not define the object, but a parent,
	 * you need to specify the form $property "Class::$propertyName", where Class is class, which the property defined.
	 *
	 * @param string|object $object Neme of class (for static property) or instance of class
	 * @param string $property Name of property
	 *
	 * @return mixed
	 */
	protected function getPropertyValue($object, $property)
	{
		if (($pos = strpos($property, '::$')) !== FALSE)
		{
			$class = substr($property, 0, $pos);
			$property = substr($property, $pos + 3);
		}

		$property = $this->getAccessibleProperty(isset($class) ? $class : $object, $property);
		return $property->getValue($object);
	}


	/**
	 * Return accessible \ReflectionProperty
	 *
	 * @param string $class Name of class
	 * @param string $name Neme of property
	 *
	 * @return \ReflectionProperty
	 */
	protected function getAccessibleProperty($class, $name)
	{
		$property = new \ReflectionProperty($class, $name);
		$property->setAccessible(TRUE);

		return $property;
	}


	/**
	 * Set directory separator based on OS
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function setDirSep($path)
	{
		return str_replace('/', DIRECTORY_SEPARATOR, $path);
	}


}
