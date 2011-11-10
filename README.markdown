XSLT Benchmarking
=================

This is project for generating, runnig a reporting XSLT Benchmarking.

Generating Tests
----------------
This part is in the subdirectory [TestsGenerator](./XSLT-Benchmarking/tree/master/TestsGenerator/).
It contains the tests generator. The generator expects templates for generating tests.
For generating the Smarty PHP library are used. Each generated test containing one XSLT file and
one or more XML files. For a better understanding see the script 'run.php'
and the example test template 'TestsTemplates/modify_element'.
