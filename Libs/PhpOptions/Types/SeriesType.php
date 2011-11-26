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
 * Series/Array type
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class SeriesType extends AType
{

	/**
	 * List of delimiters for explode input value into array
	 *
	 * @var string
	 */
	private $delimiters = ', ;|';


	/**
	 * Set object
	 *
	 * @param array $setting Array of setting of object
	 */
	public function __construct($settings = array())
	{
		parent::__construct($settings);
		if (isset($settings[0]))
		{
			$this->delimiters = $settings[0];
		}
	}


	/**
	 * Return uppercase name of type
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'ARRAY';
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
		$value = preg_replace('/[' . $this->delimiters . ']+/', ',', $value);
		return explode(',', $value);
	}


}
