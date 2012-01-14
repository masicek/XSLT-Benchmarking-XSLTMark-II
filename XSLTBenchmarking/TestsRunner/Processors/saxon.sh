#!/bin/bash

# XSLT Benchmarking
# @link https://github.com/masicek/XSLT-Benchmarking
# @author Viktor Mašíček <viktor@masicek.net>
# @license "New" BSD License

if [ "$1" == "information" ]; then
	# print information
	echo "Saxon"
	echo "http://saxon.sourceforge.net/"
	echo "6.5.5"
else
	# do transformation
	java -jar $4\Saxon\saxon-6.5.5.jar -o $3 $2 $1 2>&1
	if [ -f $3 ]; then
		echo "OK"
	fi
fi
