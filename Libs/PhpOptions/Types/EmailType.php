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
 * Email type
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class EmailType extends AType
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
		return (bool)preg_match('/[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+[.][a-zA-Z]{2,4}/', $value);
	}


}
