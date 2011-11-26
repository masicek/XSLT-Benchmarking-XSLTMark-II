<?php

/**
 * PhpOptions
 * @link git@github.com:masicek/PhpOptions.git
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace PhpOptions;

require_once __DIR__ . '/AType.php';

/**
 * Integer type
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class IntegerType extends AType
{

	/**
	 * Flag for check signed/unsigned
	 *
	 * @var bool
	 */
	private $unsigned;


	/**
	 * Set object
	 * 'unsigned' => accept only unsigned value
	 *
	 * @param array $setting Array of setting of object
	 */
	public function __construct($settings = array())
	{
		parent::__construct($settings);
		if (in_array('unsigned', $settings))
		{
			$this->unsigned = TRUE;
		}
	}


	/**
	 * Check type of value.
	 *
	 * @param mixed $value Checked value
	 *
	 * @return bool
	 */
	public function check($value)
	{
		if ($this->unsigned)
		{
			return (bool) preg_match('/^[+]?[0-9]+$/', $value);
		}
		else
		{
			return (bool) preg_match('/^[-+]?[0-9]+$/', $value);
		}
	}


	/**
	 * Return uppercase name of type
	 *
	 * @return string
	 */
	public function getName()
	{
		return parent::getName() . ($this->unsigned ? ' UNSIGNED' : '');
	}


	/**
	 * Return modified value
	 *
	 * @param mixed $value Filtered value
	 *
	 * @return mixed
	 */
	protected function useFilter($value)
	{
		return (integer)$value;
	}


}
