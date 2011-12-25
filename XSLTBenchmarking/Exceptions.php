<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking;

/**
 * List of exceptions used in XSLT Benchmarking.
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */


/**
 * Own parent of all exceptions
 */
class Exception extends \Exception
{
}

/**
 * Exceptions made by wrong calling method.
 */
class InvalidArgumentException extends Exception
{
}


/**
 * Exception generated after incorect copping file by 'copy' function
 */
class GenerateTemplateException extends Exception
{
}


/**
 * Exception generated if unknown method is called in __call
 */
class UnknownMethodException extends Exception
{
}


/**
 *
 */
class CollisionException extends Exception
{
}
