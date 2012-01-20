<?php

/*
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

// print information
if (isset($argv[1]) && $argv[1] == '--information')
{
	echo 'XSL' . PHP_EOL;
	echo 'http://php.net/manual/en/book.xsl.php' . PHP_EOL;
	echo '' . PHP_EOL;
	exit();
}


// get paths
$xslt = $argv[1];
$xml = $argv[2];
$output = $argv[3];


// do transformation
try {
	libxml_use_internal_errors(true);
	$processor = new \XSLTProcessor();
	$processor->importStylesheet(new \SimpleXMLElement($xslt, 0, TRUE));
	$outputXml = $processor->transformToXml(new \SimpleXMLElement($xml, 0, TRUE));
	if ($outputXml !== FALSE)
	{
		file_put_contents($output, $outputXml);
		$return = 'OK';
	}
	else
	{
		$return = 'Error';
	}
}
catch (\Exception $e)
{
	$error = libxml_get_last_error();
	$return = $error->message . ': line ' . $error->line . ', column ' . $error->column . ', file ' . $error->file;
}

echo $return;
