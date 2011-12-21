XSLT Benchmarking
=================

This is project for generating, runnig and reporting XSLT Benchmarking.
It can be run from command-line by [index.php](./XSLT-Benchmarking/tree/master/XSLTBenchmarking/index.php).

Usage
-----

```php
php index.php -h
```

Print help.


```php
php index.php -g
```

Generate tests from templates (use default directory for templates, tests and temporary files).


```php
php index.php -g --templates-names "modify_element,value_to_attribute"
```

Generate test from temapltes <code>modify_element</code> and <code>value_to_attribute</code> in default directory.


Generating Tests
----------------
This part is in the subdirectory [TestsGenerator/](./XSLT-Benchmarking/tree/master/XSLTBenchmarking/TestsGenerator/).
It contains the tests generator. The generator expects templates for generating tests.
Each template has to contain one XSLT template file, one or more XML files
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

* __easy__ = siple PHP test generator used for testing
