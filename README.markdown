XSLT Benchmarking
=================

This is project for generating, runnig and reporting XSLT Benchmarking.
It can be run from command-line by [index.php](./XSLT-Benchmarking/tree/master/index.php).

Usage
-----

* <code>php index.php -h</code> - print help
* <code>php index.php -g</code> - generate tests from templates (use default directory for templates, tests and temporary files)
* <code>php index.php -g --templates-names "modify_element"</code> - generate test from temaplte "modify_element" in default directory

Generating Tests
----------------
This part is in the subdirectory [TestsGenerator/](./XSLT-Benchmarking/tree/master/TestsGenerator/).
It contains the tests generator. The generator expects templates for generating tests.
Each template have to contain one XSLT template file, one or more XML files
(used as input or output files in tests) and one file contains settings
(name of generated tests, template path etc.).

Settings can be defined in different type of file, detected by extension.
Supported type of file are:

* __xml__

For generating can be used different type of templating:

* __simple__ = only copy the content of the template without modification
* __smarty__ = filter the template by [Smarty PHP library](http://www.smarty.net/)

Input XML files can be generated. For generating external XML generatr can be used.
Supported generator name are:

* __testGenerator__ = siple PHP test generator used for testing
