<?php

/*
 * XSLT Benchmarking
 *
 * Run Runner of tools scripts
 *
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Runner.php';
$runner = new XSLTBenchmark\Tools\Runner();
$runner->run();
