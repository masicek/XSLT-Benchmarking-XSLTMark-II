<?php

$options = getopt('o:c:');

$output = $options['o'];
$count = $options['c'];

$xml = new \DOMDocument();
$xml->formatOutput = true;


$root = $xml->createElement('root');

for ($i = 0; $i < $count; $i++)
{
	$el = $xml->createElement('testName');
	$text = $xml->createTextNode('Test ' . $i);
	$el->appendChild($text);
	$root->appendChild($el);
}

for ($i = 0; $i < 3; $i++)
{
	$el = $xml->createElement('testName2');
	$text = $xml->createTextNode('Test ' . $i);
	$el->appendChild($text);
	$root->appendChild($el);
}

$xml->appendChild($root);

$xml->save($output);
