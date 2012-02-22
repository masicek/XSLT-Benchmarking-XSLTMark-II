::
:: XSLT Benchmarking
:: @link https://github.com/masicek/XSLT-Benchmarking
:: @author Viktor Mašíček <viktor@masicek.net>
:: @license "New" BSD License
::
:: Arguments
:: - commandSubstr = substring from command for checking in processes list
::		- it have to be wrapped by quotes (")
:: - logPathMain = path of log file with PeakWorkingSetSize
::		- it have to be wrapped by quotes (")
:: - logPathEnd = path of log file with informatioun about ending
::		- it have to be wrapped by quotes (")
::

@echo off

:: prepare parameters
set commandSubstr=%1
set commandSubstr=%commandSubstr:~1,-1%
set logPathMain=%2
set logPathMain=%logPathMain:~1,-1%
set logPathEnd=%3
set logPathEnd=%logPathEnd:~1,-1%

:: init help variables
:: cca max 10sec
set maxLoopCountsBeforeRunning=50
:: cca max 2,5min
set maxLoopCountsRunning=1000
set loopCounter=0
set status=beforeRunnig

:loopStart

:: main checking of PeakWorkingSetSize
set errorContent=
wmic process where "CommandLine like '%%%commandSubstr%%%' and not (CommandLine like '%%windowsMemoryUsage.bat%%')" get PeakWorkingSetSize /format:table 1>> "%logPathMain%" 2> "%logPathMain%.error"
set /p errorContent= < "%logPathMain%.error"

:: beforeRunnig --> running
if "%errorContent%" == "" (
	if "%status%" == "beforeRunnig" (
		set status=running
		set loopCounter=0
	)
)
:: running --> afterRunning
if not "%errorContent%" == "" (
	if "%status%" == "running" (
		set status=afterRun
	)
)

:: afterRunning --> end this script
if "%status%" == "afterRun" (
	goto loopEnd
)

:: too many iteration in waiting for run checked command
if "%status%" == "beforeRunnig" (
	if "%loopCounter%" == "%maxLoopCountsBeforeRunning%" (
		echo LONG_LOOP_BEFORE >> "%logPathEnd%"
		echo CommandSubstr: %commandSubstr% >> "%logPathEnd%"
		goto loopEnd
	)
)

:: too many iteration in checking of running command
if "%status%" == "running" (
	if "%loopCounter%" == "%maxLoopCountsRunning%" (
		echo LONG_LOOP_RUNNING >> "%logPathEnd%"
		echo CommandSubstr: %commandSubstr% >> "%logPathEnd%"
		goto loopEnd
	)
)

set /a loopCounter=%loopCounter%+1

goto loopStart
:loopEnd

del "%logPathMain%.error"
echo LoopCounter: %loopCounter% >> "%logPathEnd%"
echo END >> "%logPathEnd%"

