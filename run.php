<?php

/**
 * Example of generating tests for XSLT Benchmarking
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */


require_once __DIR__ . '/TestsGenerator/Generator.php';


$generator = new XSLTBenchmark\TestsGenerator\Generator('./TestsTemplates', './Tests', './Tmp');
$generator->addTests('modify_element');
$generator->generateAll();
