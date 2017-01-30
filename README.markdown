XSLT Benchmarking
=================

This is project for generating, runnig and reporting XSLT Benchmarking. It was be developed for master thesis on [Faculty of Mathematics and Physics](http://www.mff.cuni.cz/) on [Charles University](http://www.cuni.cz/) in Prague, Czech Republic. Reports of our tests and API documentation are on [xsltbenchmarking.masicek.net](http://xsltbenchmarking.masicek.net/).

It can be runn on Windows and Linux. There are examples for base runnig from command line:

* Windows - ```./run.bat -grcv --repeating 10```
* Linux - ```./run.sh -grcv --repeating 10```


Next examples are demostrated with Windows variant ```./run.bat```. However, it would be same for Linux variant ```./run.sh```.


Content
-------

* [Usage](#usage)
* [Generating Tests](#generating-tests)
	* [Example](#example)
		* [Test Template](#test-template)
		* [Generated Tests](#generated-tests)
			* [Modify element - Rename](#modify-element---rename)
			* [Modify element - Remove](#modify-element---remove)
* [Running Tests](#running-tests)
* [Reporting](#reporting)
* [Convering](#converting)


Usage
-----
There are some examples of usages.

Print help.

>```
>run.bat -h
>```

Generate tests from templates (use default directory for templates, tests and temporary files).

>```
>run.bat -g
>```

Generate test from temapltes ```elements-choose``` and ```rss-reader``` in default directory.

>```
>run.bat -g --templates-dirs "elements-choose,rss-reader"
>```

Run all tests (use default directory for tests, reports and temporary files).

>```
>run.bat -r
>```

Run tests "elements-choose-long" and "rss-reader-html" in default directory.

>```
>run.bat -r --tests-dirs "elements-choose-long,rss-reader-html"
>```

Generate tests from all templates and run all generated test. Verbose mode is on. Finaly covert generated XML report into default (HTML) format.

>```
>run.bat -g -r -v -c
>```
>
>```
>run.bat -grvc
>```


Generating Tests
----------------
This part is in the subdirectory [TestsGenerator/](./XSLT-Benchmarking/tree/master/XSLTBenchmarking/TestsGenerator/). It contains the tests generator. The generator expects templates for generating tests. Each testa template is in one directory and has to contain one XSLT template file, one or more XML files (used as input or output files in tests) and one file contains settings for generating (name of generated tests, template path etc.).

Settings have to be defined in XML file ```__params.xml```.

For generating can be used different types of templating:

* __simple__ = only copy the content of the template without modification
* __smarty__ = filter the template by [Smarty PHP library](http://www.smarty.net/)
	* each setting from ```__params.xml``` is pass into Smarty as template variable: ```<setting name="testName">20</setting>```	-> ```$testName``` with vale ```20```
* __toxgene__ = filter the template by [ToXgene - the ToX XML Data Generator](http://www.cs.toronto.edu/tox/toxgene/)
	* setting ```document``` = select witch tox-document will by selected (default = first)
	* setting ```indent``` = set/unset (0/1) indent of generated XML file (default = 1)
	* setting ```seed``` = random seed for generating (default = 123456789)

Input XML files can be generated. For generating external XML generatr can be used.
Supported generator name are:

* __easy__ = siple PHP test generator used for testing
* __smarty__ = filter the template by [Smarty PHP library](http://www.smarty.net/)
	* each setting from ```__params.xml``` is pass into Smarty as template variable: ```<setting name="testName">20</setting>```	-> ```$testName``` with vale ```20```
* __toxgene__ = filter the template by [ToXgene - the ToX XML Data Generator](http://www.cs.toronto.edu/tox/toxgene/)
	* setting ```template``` = ToXGene template
	* setting ```document``` = select witch tox-document will by selected (default = first)
	* setting ```indent``` = set/unset (0/1) indent of generated XML file (default = 1)
	* setting ```seed``` = random seed for generating (default = 123456789)


### Example ###

#### Test Template ####

There is an example of one test tempalte. There is the list of files with short descriptions.

* ```./Data/TestsTemplates/modify_elements/__params.xml``` - deffinition of tests to generating
* ```./Data/TestsTemplates/modify_elements/test.tpl.xslt``` - template for generated XSLT templates
* ```./Data/TestsTemplates/modify_elements/zeroElement.xml``` - prearanged XML file
* ```./Data/TestsTemplates/modify_elements/oneElement.xml``` - prearanged XML file
* ```./Data/TestsTemplates/modify_elements/oneNewElement.xml``` - prearanged XML file

There are contents of files with descriptions.

**__params.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<tests name="Modify element" template="test.tpl.xslt" templatingType="smarty">
	<files>
		<file id="zero">zeroElement.xml</file>
		<file id="one">oneElement.xml</file>
		<file id="oneNew">oneNewElement.xml</file>
		<generated id="many" generator="easy" output="manyElements.xml">
			<setting name="testName">20</setting>
			<setting name="testName2">3</setting>
		</generated>
		<generated id="manyNew" generator="easy" output="manyNewElements.xml">
			<setting name="newTestName">20</setting>
			<setting name="testName2">3</setting>
		</generated>
	</files>
	<test name="Rename">
		<file input="one" output="oneNew" />
		<file input="many" output="manyNew" />
		<setting name="action">rename</setting>
		<setting name="newName">newTestName</setting>
	</test>
	<test name="Remove">
		<file input="one" output="zero" />
		<file input="many" output="zero" />
		<setting name="action">remove</setting>
	</test>
</tests>
```

The file ```__params.xml``` defines tests template named ```Modify element```. There are defined two tests to generating named ```Rename``` and ```Remove```. The both generated tests use two input file (```oneElement.xml``` and ```manyElements.xml```), where the first is prearranged and Easy XML generator (attribute ```/tests/files/generated/@generator```) generates the second. Attributes ```/tests/test/file/@output``` define expected outputs of transformations for XSLT template and relevant inputs. Template of generated XSLT templates is defined in attribute ```/tests/@template``` and it is common for all generated tests. The Smarty XSLT generator is used for generating XSLT template (attribute ```/tests/@templatingType```). Elements ```settings``` set settings of generated XML or XSLT files by generators.


**test.tpl.xslt**
```xslt
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output encoding="UTF-8" />

	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="@*|node()"/>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="testName">
		{if $action == 'rename'}
			<{$newName}>
				<xsl:apply-templates select="@*|node()"/>
			</{$newName}>
		{elseif $action == 'remove'}
		{else}
			<xsl:copy>
				<xsl:apply-templates select="@*|node()"/>
			</xsl:copy>
		{/if}
	</xsl:template>

</xsl:stylesheet>
```

The file ```test.tpl.xslt``` contains template of XSLT template. Generated files are affected by settings of gerated tests ```action``` in file ```__params.xml```.

**zeroElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

**oneElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<testName>Easy element 1</testName>
	<testName>Easy element 2</testName>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

**oneNewElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<newTestName>Easy element 1</newTestName>
	<newTestName>Easy element 2</newTestName>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

Files ```zeroElement.xml```, ```oneElement.xml``` and ```oneNewElement.xml``` are prearanged XML files used in generated tests.

Next two files ```manyElements.xml``` and ```manyNewElements.xml``` are generated by **easy** XML geneator.

#### Generated Tests ####

Tests are generated by command ```run.bat -g```.

##### Modify element - Rename #####

Here are the list of files of generated test ```Modify element - Rename```:

* ```./Data/TestsTemplates/modify-elements-rename/__params.xml``` - test deffinition
* ```./Data/TestsTemplates/modify-elements-rename/test.xslt``` - XSLT template uses for transformation
* ```./Data/TestsTemplates/modify-elements-rename/oneElement.xml``` - input XML file
* ```./Data/TestsTemplates/modify-elements-rename/oneNewElement.xml``` - expected output XML file
* ```./Data/TestsTemplates/modify-elements-rename/manyElements.xml``` - input XML file
* ```./Data/TestsTemplates/modify-elements-rename/manyNewElements.xml``` - expected output XML file

Here are the contents of files of test ```Modify element - Rename```.

**__params.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<test name="Modify element - Rename" template="test.xslt">
  <couple input="oneElement.xml" output="oneNewElement.xml"/>
  <couple input="manyElements.xml" output="manyNewElements.xml"/>
</test>
```

The file ```__params.xml``` contains test definition. It contains the name of the test **Modify element - Rename**, name of XSLT template **test.xslt** and two pairs of
input and expected output files.

**test.xslt**
```xslt
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output encoding="UTF-8"/>

	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="@*|node()"/>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="testName">
		<newTestName>
			<xsl:apply-templates select="@*|node()"/>
		</newTestName>
	</xsl:template>

</xsl:stylesheet>
```

**oneElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<testName>Easy element 1</testName>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

**oneNewElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<newTestName>Easy element 1</newTestName>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

**manyElements.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <testName>Easy element 1</testName>
  <testName>Easy element 2</testName>
  <testName>Easy element 3</testName>
  <testName>Easy element 4</testName>
  <testName>Easy element 5</testName>
  <testName>Easy element 6</testName>
  <testName>Easy element 7</testName>
  <testName>Easy element 8</testName>
  <testName>Easy element 9</testName>
  <testName>Easy element 10</testName>
  <testName>Easy element 11</testName>
  <testName>Easy element 12</testName>
  <testName>Easy element 13</testName>
  <testName>Easy element 14</testName>
  <testName>Easy element 15</testName>
  <testName>Easy element 16</testName>
  <testName>Easy element 17</testName>
  <testName>Easy element 18</testName>
  <testName>Easy element 19</testName>
  <testName>Easy element 20</testName>
  <testName2>Easy element 1</testName2>
  <testName2>Easy element 2</testName2>
  <testName2>Easy element 3</testName2>
</root>
```

**manyNewElements.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <newTestName>Easy element 1</newTestName>
  <newTestName>Easy element 2</newTestName>
  <newTestName>Easy element 3</newTestName>
  <newTestName>Easy element 4</newTestName>
  <newTestName>Easy element 5</newTestName>
  <newTestName>Easy element 6</newTestName>
  <newTestName>Easy element 7</newTestName>
  <newTestName>Easy element 8</newTestName>
  <newTestName>Easy element 9</newTestName>
  <newTestName>Easy element 10</newTestName>
  <newTestName>Easy element 11</newTestName>
  <newTestName>Easy element 12</newTestName>
  <newTestName>Easy element 13</newTestName>
  <newTestName>Easy element 14</newTestName>
  <newTestName>Easy element 15</newTestName>
  <newTestName>Easy element 16</newTestName>
  <newTestName>Easy element 17</newTestName>
  <newTestName>Easy element 18</newTestName>
  <newTestName>Easy element 19</newTestName>
  <newTestName>Easy element 20</newTestName>
  <testName2>Easy element 1</testName2>
  <testName2>Easy element 2</testName2>
  <testName2>Easy element 3</testName2>
</root>
```

##### Modify element - Remove #####

There are the list of files of generated test ```Modify element - Remove```:

* ```./Data/TestsTemplates/modify-elements-remove/__params.xml``` - test deffinition
* ```./Data/TestsTemplates/modify-elements-remove/test.xslt``` - XSLT template uses for transformation
* ```./Data/TestsTemplates/modify-elements-remove/oneElement.xml``` - input XML file
* ```./Data/TestsTemplates/modify-elements-remove/manyElements.xml``` - input XML file
* ```./Data/TestsTemplates/modify-elements-remove/zeroElement.xml``` - expected output XML file

There are contens of files of test ```Modify element - Remove```.


**__params.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<test name="Modify element - Remove" template="test.xslt">
  <couple input="oneElement.xml" output="zeroElement.xml"/>
  <couple input="manyElements.xml" output="zeroElement.xml"/>
</test>
```

The file ```__params.xml``` contains test definition. It contains the name of the test **Modify element - Remove**, name of XSLT template **test.xslt** and two pairs of
input and expected output files.


**test.xslt**
```xslt
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output encoding="UTF-8"/>

	<xsl:template match="node( ) | @*">
		<xsl:copy>
			<xsl:apply-templates select="@*|node()"/>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="testName"/>

</xsl:stylesheet>
```

**oneElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<testName>Easy element 1</testName>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

**manyElements.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <testName>Easy element 1</testName>
  <testName>Easy element 2</testName>
  <testName>Easy element 3</testName>
  <testName>Easy element 4</testName>
  <testName>Easy element 5</testName>
  <testName>Easy element 6</testName>
  <testName>Easy element 7</testName>
  <testName>Easy element 8</testName>
  <testName>Easy element 9</testName>
  <testName>Easy element 10</testName>
  <testName>Easy element 11</testName>
  <testName>Easy element 12</testName>
  <testName>Easy element 13</testName>
  <testName>Easy element 14</testName>
  <testName>Easy element 15</testName>
  <testName>Easy element 16</testName>
  <testName>Easy element 17</testName>
  <testName>Easy element 18</testName>
  <testName>Easy element 19</testName>
  <testName>Easy element 20</testName>
  <testName2>Easy element 1</testName2>
  <testName2>Easy element 2</testName2>
  <testName2>Easy element 3</testName2>
</root>
```

**zeroElement.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
	<testName2>Easy element 1</testName2>
	<testName2>Easy element 2</testName2>
	<testName2>Easy element 3</testName2>
</root>
```

Running Tests
-------------

Print processors that are available on current machine to testing.

>```
>run.bat -a
>```

Run all tests (use default directory for tests, reports and temporary files).

>```
>run.bat -r
>```

Run all tests, but only in processors "Saxon 6.5.5" and "Xalan 2.7.1".

>```
>run.bat -r -p "saxon655,xalan271"
>```

Run test that are in direcotry ```../MyData/Tests/modify-elements-remove```. Test is run only in processors "Saxon 6.5.5" and "Xalan 2.7.1". Each transformation are repeated 10 times. Reports are genereted into directory ```../MyData/MyReports```

>```
>run.bat -r -p "saxon655,xalan271" --tests "../MyData/MyTests" --tests-dirs "modify-elements-remove" --repeating 10 --reports "../MyData/MyReports"
>```

Reporting
---------

Reports are generated into XML file after running tests. Generated file have name based on actual time with format ```[YYYY-MM-DD-HH-mm-ss].xml``` and saved in directory set by option ```--reports```.

Merge reports ```2012-03-04-21-25-08.xml``` and ```myRenamedReposrts.xml``` into one report file ```2012-03-08-14-48-20-merge.xml```. Generated merged reports have name based on actual time with suffix ```-merge```.

>```
>run.but -m "2012-03-04-21-25-08.xml,myRenamedReposrts.xml" --reports "../MyData/MyReports"
>```


Converting
----------

Reports in XML format can be converted into HTML format.

Convert the latest report in set directory ```../MyData/MyReports```.

>```
>run.bat -c --reports "../MyData/MyReports"
>```

Convert set report ```myReport.xml``` in set directory ```../MyData/MyReports```.

>```
>run.bat -c myReport.xml --reports "../MyData/MyReports"
>```
