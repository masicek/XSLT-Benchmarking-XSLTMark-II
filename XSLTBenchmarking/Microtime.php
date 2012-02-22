<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking;

/**
 * Static class for working with microtime with sufficient precision
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Microtime
{

	/**
	 * Default scale of time decimal precision
	 */
	const SCALE = 6;


	/**
	 * Make this class static
	 *
	 * @codeCoverageIgnore
	 */
	private function __construct()
	{
	}


	/**
	 * Get current time stamp with sufficient precision
	 *
	 * @param int $scale Scale of time decimal precision
	 *
	 * @return string
	 */
	public static function now($scale = self::SCALE)
	{
		list($usec, $sec) = explode(' ', microtime());
		return bcadd($sec, $usec, $scale);
	}


	/**
	 * Return microtime in humanreadable form
	 *
	 * @param string $microtime Timestamp returned by another function in this class
	 *
	 * @return string
	 */
	public static function humanReadable($microtime)
	{
		list($sec, $usec) = explode('.', $microtime);

		$days = floor($sec / 86400);

		$sec -= $days * 86400;
		$hours = floor($sec / 3600);

		$sec -= $hours * 3600;
		$minutes = floor($sec / 60);

		$sec -= $minutes * 60;

		if (strlen($hours) == 1)
		{
			$hours = '0' . $hours;
		}
		if (strlen($minutes) == 1)
		{
			$minutes = '0' . $minutes;
		}
		if (strlen($sec) == 1)
		{
			$sec = '0' . $sec;
		}

		$result = $hours . ':' . $minutes . ':' . $sec . '.' . $usec;

		if ($days)
		{
			$result = $days . 'days ' . $result;
		}

		return $result;
	}


	/**
	 * Get substrast of time stamps with sufficient precision
	 *
	 * @param string $leftOperand Left operand
	 * @param string $rightOperand Right operand
	 * @param int $scale Scale of time decimal precision
	 *
	 * @return string = $leftOperand - $rightOperand
	 */
	public static function substract($leftOperand, $rightOperand, $scale = self::SCALE)
	{
		return bcsub($leftOperand, $rightOperand, $scale);
	}


	/**
	 * Sum all arguments
	 *
	 * @param array $operands List of operands for sum
	 * @param int $scale Scale of time decimal precision
	 *
	 * @return string
	 */
	public static function sum($operands, $scale = self::SCALE)
	{
		if (count($operands) <= 0)
		{
			return '0.' . str_repeat('0', $scale);
		}

		$result = array_shift($operands);

		foreach ($operands as $operand)
		{
			$result = bcadd($result, $operand, $scale);
		}

		return $result;
	}


	/**
	 * Get division of time stamps with sufficient precision
	 *
	 * @param string $leftOperand Left operand
	 * @param string $rightOperand Right operand
	 * @param int $scale Scale of time decimal precision
	 *
	 * @return string = $leftOperand / $rightOperand
	 */
	public static function divide($leftOperand, $rightOperand, $scale = self::SCALE)
	{
		return bcdiv($leftOperand, $rightOperand, $scale);
	}


}
