#!/bin/bash

buildIn=`php -n -m | grep mbstring | wc -l`
extension=`find /usr/lib/php5/ -type f | grep '/mbstring.so'`

if [ "$buildIn" == "1" ]; then
	commandPrefix='php -n'
else
	if [ "$extension" != "" ]; then
		commandPrefix="php -n -d extension=$extension"
	else
		echo 'PHP lib "mbstring" is not supported. You have to install it into PHP.';
		return
	fi
fi

$commandPrefix ./XSLTBenchmarking/index.php $*
