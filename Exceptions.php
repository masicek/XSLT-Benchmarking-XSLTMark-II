<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmark;

/**
 * List of exceptions used in XSLT Benchmarking.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */


/**
 * Exceptions made by wrong calling method.
 */
class InvalidArgumentException extends \Exception
{
}


/**
 * Exception generated after incorect copping file by 'copy' function
 */
class CopyFileException extends \Exception
{
}
