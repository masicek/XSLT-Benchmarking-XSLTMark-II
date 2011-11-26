<?php

/**
 * PhpOptions
 * @link git@github.com:masicek/PhpOptions.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpOptions;

require_once __DIR__ . '/Exceptions.php';
require_once __DIR__ . '/Arguments.php';

/**
 * Class for better work with PHP comand-line options
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Options
{

	/**
	 * Version of PhpOptions
	 */
	const VERSION = '0.9.0';

	/**
	 * List of possible options
	 *
	 * @var array of Option
	 */
	private $options = array();

	/**
	 * List of value of deffined options
	 *
	 * @var array ([name of option] => [value of option])
	 */
	private $optionsValues = array();

	/**
	 * Default option and its value
	 *
	 * @var array ('name' => [name of option], 'value' => [value of option])
	 */
	private $default = NULL;

	/**
	 * Common description show in help
	 *
	 * @var string
	 */
	private $description = '';

	/**
	 * List groups with names of options that belong to groups
	 *
	 * @var array ([name of group] => array of names of options)
	 */
	private $groups = array();


	/**
	 * Control that script run from command line
	 *
	 * @throws UserBadCallException Script have to run from command line.
	 */
	public function __construct()
	{
		if (php_sapi_name() !== 'cli')
		{
			throw new UserBadCallException('Script have to run from command line.');
		}
	}


	/**
	 * Return array of arguments in command-line.
	 *
	 * @return array
	 */
	public static function arguments()
	{
		return Arguments::arguments();
	}


	/**
	 * Add option
	 *
	 * @param array|Option $options Added options
	 *
	 * @return Options
	 */
	public function add($options = array())
	{
		if (!is_array($options))
		{
			$options = array($options);
		}

		foreach ($options as $option)
		{
			$this->addOne($option);
		}

		return $this;
	}


	/**
	 * Set default option if any options is set
	 *
	 * @param string $name Name of option
	 *
	 * @throws InvalidArgumentException Unknown option.
	 * @return Options
	 */
	public function def($name)
	{
		if (!isset($this->options[$name]))
		{
			throw new InvalidArgumentException($name . ': Unknown option.');
		}

		$value = $this->options[$name]->getDef();
		$this->default['name'] = $name;
		$this->default['value'] = $value;
		if (is_null($value))
		{
			$this->default['value'] = TRUE;
		}

		return $this;
	}


	/**
	 * Set text of common description in generated help
	 *
	 * @param string $description Text of common description
	 *
	 * @return Options
	 */
	public function description($description)
	{
		$this->description = $description;
		return $this;
	}


	/**
	 * Return value of option.
	 * For set option without value return TRUE.
	 * If option is not set, return FALSE.
	 *
	 * @param string $name Name of option
	 *
	 * @throws InvalidArgumentException Unknown option
	 * @return mixed
	 */
	public function get($name)
	{
		if (!isset($this->optionsValues[$name]))
		{
			throw new InvalidArgumentException($name . ': Unknown option.');
		}

		// default option
		if (!is_null($this->default) && ($this->default['name'] == $name) && (count(Arguments::options()) == 0))
		{
			return $this->default['value'];
		}

		return $this->optionsValues[$name];
	}


	/**
	 * Define dependences of options.
	 *
	 * @param string $main Main option for which we define needed options
	 * @param string|array $needed Needed options for main option
	 *
	 * @throws InvalidArgumentException Unknown option
	 * @throws UserBadCallException Some option need some another option
	 * @return Options
	 */
	public function dependences($main, $needed)
	{
		if (!is_array($needed))
		{
			$needed = array($needed);
		}

		if (!isset($this->optionsValues[$main]))
		{
			throw new InvalidArgumentException($name . ': Unknown option.');
		}

		$neededOptions = array();
		foreach ($needed as $name)
		{
			if (!isset($this->optionsValues[$name]))
			{
				throw new InvalidArgumentException($name . ': Unknown option.');
			}
			$neededOptions[] = $this->options[$name];
		}

		// if main option is defined, check needed options
		if ($this->get($main) !== FALSE)
		{
			foreach ($needed as $name)
			{
				if ($this->get($name) === FALSE)
				{
					$mainOptions = $this->options[$main]->getOptions();
					$nameOptions = $this->options[$name]->getOptions();
					throw new UserBadCallException('Option "' . $mainOptions . '" needs option "'. $nameOptions . '".');
				}
			}
		}

		$this->options[$main]->dependences($neededOptions);

		return $this;
	}


	/**
	 * Define groups of options
	 *
	 * @param string $name Name of group
	 * @param string|array $options List of options
	 *
	 * @return Options
	 */
	public function group($name, $options)
	{
		if (in_array($name, $this->groups))
		{
			throw new LogicException($name . ': Group already exists.');
		}

		if (!is_array($options))
		{
			$options = array($options);
		}

		foreach ($options as $optionName)
		{
			if (!isset($this->options[$optionName]))
			{
				throw new LogicException($optionName . ': Option does not exist.');
			}
		}

		$this->groups[$name] = $options;

		return $this;
	}


	/**
	 * Return "help" made from descriptions of options
	 *
	 * @return string
	 */
	public function getHelp()
	{
		$help = $this->description . "\n\n";

		$options = $this->options;

		$indent = 0;
		if ($this->groups)
		{
			foreach ($this->groups as $groupName => $optionsNames)
			{
				$help .= $groupName . "\n";
				foreach ($optionsNames as $optionName)
				{
					$help .= $options[$optionName]->getHelp(1) . "\n";
					unset($options[$optionName]);
				}
			}
			$help .= "\nNON GROUP OPTIONS:\n";
			$indent = 1;
		}

		// print non group options
		foreach ($options as $option)
		{
			$help .= $option->getHelp($indent) . "\n";
		}

		return $help;
	}


	// ---- private ----


	/**
	 * Add one option
	 *
	 * @param Option $option Added option
	 *
	 * @return void
	 */
	private function addOne(Option $option)
	{
		$this->checkConflicts($option);
		$this->options[$option->getName()] = $option;
		$this->optionsValues[$option->getName()] = $option->getValue((bool)($this->default['value']));
	}


	/**
	 * Check conflict between added option and input option
	 *
	 * @param Option $option Checked option
	 *
	 * @throws LogicException Option already exist.
	 * @throws LogicException Option with set short variant already exist.
	 * @throws LogicException Option with set long variant already exist.
	 * @return void
	 */
	private function checkConflicts(Option $option)
	{
		$name = $option->getName();
		if (isset($this->options[$name]))
		{
			throw new LogicException($name . ': Option already exists.');
		}

		$short = $option->getShort();
		if (in_array($short, $this->getAllShorts()))
		{
			throw new LogicException($name . ': Option with short variant "' . $short . '" already exists.');
		}

		$long = $option->getLong();
		if (in_array($long, $this->getAllLongs()))
		{
			throw new LogicException($name . ': Option with long variant "' . $long . '" already exists.');
		}
	}


	/**
	 * Return list of all shorts options defined in aded options
	 * @todo cache returned value
	 *
	 * @return array
	 */
	private function getAllShorts()
	{
		$shorts = array();
		foreach ($this->options as $option)
		{
			$short = $option->getShort();
			if (!is_null($short))
			{
				$shorts[] = $short;
			}
		}
		return $shorts;
	}


	/**
	 * Return list of all longs options defined in aded options
	 * @todo cache returned value
	 *
	 * @return array
	 */
	private function getAllLongs()
	{
		$longs = array();
		foreach ($this->options as $option)
		{
			$long = $option->getLong();
			if (!is_null($long))
			{
				$longs[] = $long;
			}
		}
		return $longs;
	}


}
