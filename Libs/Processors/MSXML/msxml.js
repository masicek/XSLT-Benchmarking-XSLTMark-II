// make/replace file and write content into it
function createFile(filePath, content)
{
	var file = WScript.CreateObject("Scripting.FileSystemObject");
	var fileHandler = file.CreateTextFile(filePath, true);
	fileHandler.write(content);
}

// Arguments: [XML] [XSLT] [OUTPUT] [ERROR] [VERSION]
var oArgs = WScript.Arguments;
xmlFile = oArgs(0);
xsltFile = oArgs(1);
outputFile = oArgs(2);
errorFile = oArgs(3);
version = oArgs(4);

var error = '';

// input XML
var xml = new ActiveXObject("MSXML2.DOMDocument." + version);
xml.validateOnParse = false;
xml.async = false;
xml.load(xmlFile);
if (xml.parseError.errorCode != 0)
{
	createFile(errorFile, "XML Parse Error : " + xml.parseError.reason);
	WScript.Quit(1);
}

// XSLT template
var xslt = new ActiveXObject("MSXML2.DOMDocument." + version);
xslt.validateOnParse = false;
xslt.async = false;
xslt.load(xsltFile);
if (xslt.parseError.errorCode != 0)
{
	createFile(errorFile, "XSLT Parse Error : " + xslt.parseError.reason);
	WScript.Quit(1);
}

// transformation
try
{
	createFile(outputFile, xml.transformNode(xslt));
}
catch(err)
{
	createFile(errorFile, "Transformation Error : " + err.number + "*" + err.description);
}

