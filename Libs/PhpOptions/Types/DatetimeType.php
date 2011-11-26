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
 * Datetime type
 *
 * Format: YEAR(-|.)MONTH(-|.)DAY[ HOURS[(-|:)MINUTES[(-|:)SECONDS]][ HOURS_FORMAT]]
 * YEAR = four-digit number
 * MONTH = one-digit or two-digit number or short name (three character)
 * DAY = one-digit or two-digit number
 * HOURS = one-digit or two-digit number
 * MINUTES = one-digit or two-digit number
 * SECONDS = one-digit or two-digit number
 * HOURS_FORMAT = hour format = (AM|am|A|a|PM|pm|P|p)
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class DatetimeType extends AType
{

	/**
	 * Filtered value return as timestamp
	 *
	 * @var bool
	 */
	private $timestamp = FALSE;


	/**
	 * Set object
	 *
	 * @param array $setting Array of setting of object
	 */
	public function __construct($settings = array())
	{
		parent::__construct($settings);
		if (in_array('timestamp', $settings))
		{
			$this->timestamp = TRUE;
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
		$dateString = $this->getDatetimeString($value);

		$isDate = FALSE;
		if ($dateString)
		{
			// check validation
			try {
				$dateObj = new \DateTime($dateString);
				$isDate = ($dateObj) ? TRUE : FALSE;
			} catch (\Exception $e) {
				$isDate = FALSE;
			}
		}

		return $isDate;
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
		$dateString = $this->getDatetimeString($value);
		$date = FALSE;
		if ($dateString)
		{
			$date = new \DateTime($dateString);
			if ($this->timestamp)
			{
				$date = $date->getTimestamp();
			}
		}
		return $date;
	}


	/**
	 * Return input date formated for DateTime object.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function getDatetimeString($value)
	{
		// parse value
		$match = preg_match('/^([0-9]{4})[-.]([0-9a-zA-Z]+)[-.]([0-9]{1,2})( +([0-9]{1,2})([-:]([0-9]{1,2})([-:]([0-9]{1,2}))?)?( *(AM|am|A|a|PM|pm|P|p))?)?$/', $value, $matches);
		$date = '';
		if ($match)
		{
			// prepare date string
			$year = $matches[1];
			$month = $this->complete($matches[2]);
			$day = $this->complete($matches[3]);
			$hours = $this->complete(isset($matches[5]) ? $matches[5] : '');
			$minutes = $this->complete(isset($matches[7]) ? $matches[7] : '');
			$seconds = $this->complete(isset($matches[9]) ? $matches[9] : '');
			$hoursFormat = isset($matches[11]) ? $matches[11] : '';
			if (strlen($hoursFormat) == 1)
			{
				$hoursFormat = $hoursFormat . 'M';
			}
			$hoursFormat = strtoupper($hoursFormat);
			$date = $year . '-' . $month . '-' . $day . ' ' . $hours . ':' . $minutes . ':' . $seconds . $hoursFormat;
		}

		return $date;
	}


	/**
	 * Add zero if input value have only one character
	 * Return two zero '00' if input value have not any character
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function complete($value)
	{
		$length = strlen($value);
		if ($length == 1)
		{
			$value = '0' . $value;
		}
		elseif ($length == 0)
		{
			$value = '00';
		}

		return $value;
	}


}
