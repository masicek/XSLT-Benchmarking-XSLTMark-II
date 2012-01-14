:: XSLT Benchmarking
:: @link https://github.com/masicek/XSLT-Benchmarking
:: @author Viktor Mašíček <viktor@masicek.net>
:: @license "New" BSD License

@echo off

IF %1 == information (
	:: print information
	ECHO Saxon
	ECHO http://saxon.sourceforge.net/
	ECHO 6.5.5
) ELSE (
	:: do transformation
	java -jar %4\Saxon\saxon-6.5.5.jar -o %3 %2 %1 2>&1
	IF exist "%3" (
		ECHO OK
	)
)
