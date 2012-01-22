<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking;

/**
 * Printing information text
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class Printer
{


	/**
	 * Constant for production mode of printing
	 */
	const MODE_PRODUCTION = 'production';

	/**
	 * Constant for production mode of printing
	 */
	const MODE_TEST = 'test';

	/**
	 * Selected mode of printing
	 *
	 * @var string
	 */
	public static $mode = self::MODE_PRODUCTION;


	/**
	 * Make this class static
	 *
	 * @codeCoverageIgnore
	 */
	private function __construct()
	{
	}


	/**
	 * Print header
	 *
	 * @param string $header Text of printed header
	 *
	 * @return void
	 */
	public static function header($header)
	{
		$header = $header . ':';
		$line = str_repeat('-', strlen($header));
		self::info($header);
		self::info($line);
	}


	/**
	 * Print information text
	 *
	 * @param string $info Text of printed info
	 *
	 * @return void
	 */
	public static function info($info = '')
	{
		switch (self::$mode)
		{
			case self::MODE_PRODUCTION:// @codeCoverageIgnoreStart
				fwrite(STDOUT, $info . PHP_EOL);
				break;// @codeCoverageIgnoreEnd

			case self::MODE_TEST:
				echo $info . PHP_EOL;
				break;
		}
	}

}
