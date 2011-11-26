<?php

/**
 * PhpOptions
 * @link git@github.com:masicek/PhpOptions.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpOptions;

/**
 * Class for better work with defined types extends ITypes
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Types
{

	/**
	 * Types of options value
	 */
	const TYPE_STRING = 'string';
	const TYPE_CHAR = 'char';
	const TYPE_INTEGER = 'integer';
	const TYPE_REAL = 'real';
	const TYPE_DATE = 'date';
	const TYPE_TIME = 'time';
	const TYPE_DATETIME = 'datetime';
	const TYPE_DIRECTORY = 'directory';
	const TYPE_FILE = 'file';
	const TYPE_EMAIL = 'email';
	const TYPE_ENUM = 'enum';
	const TYPE_SERIES = 'series';


	/**
	 * List of possible types of options values
	 *
	 * @return array
	 */
	public static function possibleTypes()
	{
		return array(
			self::TYPE_STRING,
			self::TYPE_CHAR,
			self::TYPE_INTEGER,
			self::TYPE_REAL,
			self::TYPE_DATE,
			self::TYPE_TIME,
			self::TYPE_DATETIME,
			self::TYPE_DIRECTORY,
			self::TYPE_FILE,
			self::TYPE_EMAIL,
			self::TYPE_ENUM,
			self::TYPE_SERIES,
		);
	}


	/**
	 * Get class for specifis type of option value.
	 *
	 * @param string $type Type of option value
	 * @param array $settings List of setting specific for selected type
	 *
	 * @throws InvalidArgumentException Undefined type of option.
	 * @return IType
	 */
	public static function getType($type, $settings)
	{
		if (in_array($type, self::possibleTypes()))
		{
			$class = ucfirst($type) . 'Type';
			require_once __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
			$class = 'PhpOptions\\' . $class;
			return new $class($settings);
		}
		else
		{
			throw new InvalidArgumentException($type . ': Undefined type of option.');
		}
	}


}
