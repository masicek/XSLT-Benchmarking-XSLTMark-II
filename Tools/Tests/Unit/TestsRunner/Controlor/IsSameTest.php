<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace Tests\XSLTBenchmarking\TestsRunner\Controlor;

use \Tests\XSLTBenchmarking\TestCase;
use \XSLTBenchmarking\TestsRunner\Controlor;

require_once ROOT_TOOLS . '/TestsRunner/Controlor.php';

/**
 * IsSameTest
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 *
 * @covers \XSLTBenchmarking\TestsRunner\Controlor::isSame
 * @covers \XSLTBenchmarking\TestsRunner\Controlor::normalizeXml
 * @covers \XSLTBenchmarking\TestsRunner\Controlor::sortAttributes
 */
class IsSameTest extends TestCase
{


	private $controlor;


	public function setUp()
	{
		$this->controlor = new Controlor();
	}


	public function testWrongFilePath1()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->controlor->isSame('unknown', __FILE__);
	}


	public function testWrongFilePath2()
	{
		$this->setExpectedException('\PhpPath\NotExistsPathException');
		$this->controlor->isSame(__FILE__, 'unknown');
	}


	public function testPlainTextIsSame()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.txt');
		$file2 = $this->setDirSep(__DIR__ . '/file2.txt');

		file_put_contents($file1, "Lorm ipsum \n    \tdolor");
		file_put_contents($file2, "Lorm ipsum \n    \tdolor");

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertTrue($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testPlainTextIsNotSame()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.txt');
		$file2 = $this->setDirSep(__DIR__ . '/file2.txt');

		file_put_contents($file1, "Lorm ipsum \n    \tdolor");
		file_put_contents($file2, "Lorm ipsum \ndolor");

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsSame()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.txt');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root rootAttribute1 =    "xxx"   rootAttribute2="yyy">' . PHP_EOL .
		'		<element1 attribute1 =  "aaa"     attribute2="bbb"   >Lorem ipsum</element1 >' . PHP_EOL .
		'		<empty ></empty  >' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root rootAttribute2="yyy" rootAttribute1="xxx"><element1 attribute2="bbb" attribute1="aaa">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty/></root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertTrue($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentElementName()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element2 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element2>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentAttributeName()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 Xattribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentElementContent()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem <subelement/> ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem <subelement/> ipsum dolor</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentAttributeContent()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="ccc"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentRootElement()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<rootX>' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</rootX>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentRootAttributeName()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root attributeA="aaa">' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root attributeB="aaa">' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


	public function testXmlIsNotSameDifferentRootAttributeValue()
	{
		$file1 = $this->setDirSep(__DIR__ . '/file1.xml');
		$file2 = $this->setDirSep(__DIR__ . '/file2.xml');

		$xml1 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root attribute="aaa">' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';
		$xml2 = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
		'	<root attribute="bbb">' . PHP_EOL .
		'		<element1 attribute1="aaa"     attribute2="bbb">Lorem ipsum</element1>' . PHP_EOL .
		'		<empty></empty>' . PHP_EOL .
		'	</root>';

		file_put_contents($file1, $xml1);
		file_put_contents($file2, $xml2);

		$comparison = $this->controlor->isSame($file1, $file2);

		$this->assertFalse($comparison);

		unlink($file1);
		unlink($file2);
	}


}
