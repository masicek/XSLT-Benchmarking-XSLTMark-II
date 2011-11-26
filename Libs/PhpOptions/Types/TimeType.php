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
 * Time type
 *
 * Format: HOURS[(-|:)MINUTES[(-|:)SECONDS]][ HOURS_FORMAT]
 * HOURS = one-digit or two-digit number
 * MINUTES = one-digit or two-digit number
 * SECONDS = one-digit or two-digit number
 * HOURS_FORMAT = hour format = (AM|am|A|a|PM|pm|P|p)
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TimeType extends AType
{


	/**
	 * Check type of value.
	 *
	 * @param mixed $value Checked value
	 *
	 * @return bool
	 */
	public function check($value)
	{
		$dateString = $this->getTimeString($value);

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
		return $this->getTimeString($value);
	}


	/**
	 * Return input date formated for DateTime object.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function getTimeString($value)
	{
		// parse value
		$match = preg_match('/^([0-9]{1,2})([-:]([0-9]{1,2})([-:]([0-9]{1,2}))?)?( *(AM|am|A|a|PM|pm|P|p))?$/', $value, $matches);
		$date = '';
		if ($match)
		{
			// prepare date string
			$hours = $this->complete(isset($matches[1]) ? $matches[1] : '');
			$minutes = $this->complete(isset($matches[3]) ? $matches[3] : '');
			$seconds = $this->complete(isset($matches[5]) ? $matches[5] : '');
			$hoursFormat = isset($matches[11]) ? $matches[11] : '';
			if (strlen($hoursFormat) == 1)
			{
				$hoursFormat = $hoursFormat . 'M';
			}
			$hoursFormat = strtoupper($hoursFormat);
			$date = $hours . ':' . $minutes . ':' . $seconds . $hoursFormat;
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
