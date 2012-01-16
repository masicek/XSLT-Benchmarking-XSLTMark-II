<?php

/*
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

// print information
if (isset($argv[1]) && $argv[1] == 'information')
{
	echo 'XSLT (PHP4)' . PHP_EOL;
	echo 'http://www.php.net/manual/en/book.xslt.php' . PHP_EOL;
//	echo xslt_backend_version() . PHP_EOL;
	echo '' . PHP_EOL;
	exit();
}


// get paths
$xslt = $argv[1];
$xml = $argv[2];
$output = $argv[3];

// do transformation
$xh = xslt_create();
if (xslt_process($xh, $xml, $xslt, $output))
{
	$return = 'OK';
}
else
{
	$result = xslt_error($xh);
}

xslt_free($xh);


echo $return;
