<?php

/**
 * PhpOptions
 * @link git@github.com:masicek/PhpOptions.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpOptions;

/**
 * Static class for getting options and argumets from command-line.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Arguments
{

	/**
	 * List of options with their values readed form command-line.
	 *
	 * @var array
	 */
	private static $options = NULL;

	/**
	 * List of arguments readed form command-line.
	 *
	 * @var array
	 */
	private static $arguments = NULL;


	/**
	 * Return array of options with their values in command-line.
	 * Options are prepared for better work.
	 * <pre>
	 * --abc="xxx" => ('abc' => 'xxx')
	 * --abc ="xxx" => ('abc' => 'xxx')
	 * --abc "xxx" => ('abc' => 'xxx')
	 * -abc="xxx" => ('a' => TRUE, 'b' => TRUE, 'c' => 'xxx')
	 * -abc "xxx" => ('a' => TRUE, 'b' => TRUE, 'c' => 'xxx')
	 * -abc ="xxx" => ('a' => TRUE, 'b' => TRUE, 'c' => 'xxx')
	 * -a -bc ="xxx" => ('a' => TRUE, 'b' => TRUE, 'c' => 'xxx')
	 * </pre>
	 *
	 * @return array ([option] => [value], ...)
	 */
	public static function options()
	{
		self::setAll();
		return self::$options;
	}


	/**
	 * Return array of arguments in command-line.
	 *
	 * @return array
	 */
	public static function arguments()
	{
		self::setAll();
		return self::$arguments;
	}


	/**
	 * Set options and arguments from command-line into internal parameters,
	 * if parameters are empty.
	 *
	 * @return void
	 */
	private static function setAll()
	{
		if (is_null(self::$options) || is_null(self::$arguments))
		{
			$all = self::readAll();
			self::$options = $all['options'];
			self::$arguments = $all['arguments'];
		}
	}


	/**
	 * Read array of options and arguments in command-line.
	 *
	 * @return array ([options], [arguments])
	 */
	private static function readAll()
	{
		$cmdOptions = array();
		$cmdArguments = array();
		$args = $_SERVER['argv'];

		// delete script name
		array_shift($args);

		if (count($args) > 0)
		{
			// clean arguments
			$argsClean = array();
			foreach ($args as $arg)
			{
				$position = strpos($arg, '=');
				if ($position === 0)
				{
					$argsClean[] = substr($arg, 1);
				}
				elseif ($position !== FALSE)
				{
					$argsClean[] = substr($arg, 0, $position);
					$argsClean[] = substr($arg, $position + 1);
				}
				else
				{
					$argsClean[] = $arg;
				}
			}
			$args = $argsClean;

			$previous = NULL;
			foreach ($args as $arg)
			{
				// long option
				if (substr($arg, 0, 2) == '--')
				{
					$option = substr($arg, 2);
					$cmdOptions[$option] = TRUE;
					$previous = $option;
				}
				// short options (exception for signed integer and real)
				elseif (substr($arg, 0, 1) == '-' && !(preg_match('/^-[0-9]+([.,][0-9]+)?$/', $arg)))
				{
					// exception for signed integer and real
					if (preg_match('/^[0-9]+$/', substr($arg, 1)))
					{
						// it is value
					}

					foreach (str_split(substr($arg, 1)) as $char)
					{
						$cmdOptions[$char] = TRUE;
						$previous = $char;
					}
				}
				// value for previous option
				elseif ($previous)
				{
					$cmdOptions[$previous] = $arg;
					$previous = NULL;
				}
				// arguments
				else
				{
					$cmdArguments[] = $arg;
				}
			}
		}

		return array('options' => $cmdOptions, 'arguments' => $cmdArguments);
	}


}
